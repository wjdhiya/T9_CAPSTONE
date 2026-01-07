<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PenelitianController extends Controller
{
    public function index(Request $request)
    {
        $query = Penelitian::with('user', 'verifiedBy');

        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isKaprodi()) {
            User::where('id', $user->id)->update(['kaprodi_seen_penelitian_at' => now()]);
        }

        if ($user && $user->isDosen() && !$user->canVerify()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_penelitian', 'like', "%{$search}%")
                    ->orWhere('abstrak', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', 'like', $request->tahun . '%');
        }

        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('status_verifikasi') && $request->status_verifikasi != '') {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $stats = [
            'total' => Penelitian::count(),
            'verified' => Penelitian::where('status_verifikasi', 'verified')->count(),
            'pending' => Penelitian::where('status_verifikasi', 'pending')->count(),
            'selesai' => Penelitian::where('status', 'selesai')->count(),
        ];

        if ($user && $user->isDosen() && !$user->canVerify()) {
            $stats = [
                'total' => Penelitian::where('user_id', $user->id)->count(),
                'verified' => Penelitian::where('user_id', $user->id)->where('status_verifikasi', 'verified')->count(),
                'pending' => Penelitian::where('user_id', $user->id)->where('status_verifikasi', 'pending')->count(),
                'selesai' => Penelitian::where('user_id', $user->id)->where('status', 'selesai')->count(),
            ];
        }

        if ($request->query('show_all') === '1') {
            $penelitian = $query->latest()->get();
        } else {
            $penelitian = $query->latest()->paginate(10);
        }

        return view('penelitian.index', compact('penelitian', 'stats'));
    }

    public function create()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan data.');
        }

        return view('penelitian.create');
    }

    public function store(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan data.');
        }

        $validated = $request->validate([
            'judul_penelitian' => 'required|string|max:500',
            'abstrak' => 'nullable|string',
            'jenis' => 'required|in:internal,eksternal,mandiri,hibah_internal,hibah_eksternal,kerjasama',
            'sumber_dana' => 'nullable|string|max:255',
            'anggaran' => 'nullable|numeric|min:0',
            'tahun' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catatan' => 'nullable|string',
            'anggota_peneliti' => 'nullable|array',
            'anggota_peneliti.*.nama' => 'required_with:anggota_peneliti|string',
            'anggota_peneliti.*.nip' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status_verifikasi'] = 'pending';

        if ($request->has('anggota_peneliti')) {
            $validated['anggota'] = json_encode(array_values($request->anggota_peneliti));
        }

        if ($request->hasFile('file_proposal')) {
            $originalName = $request->file('file_proposal')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_proposal'] = $request->file('file_proposal')->storeAs('penelitian/proposal', $safeName, 'public');
        }

        if ($request->hasFile('file_laporan')) {
            $originalName = $request->file('file_laporan')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_laporan'] = $request->file('file_laporan')->storeAs('penelitian/laporan', $safeName, 'public');
        }

        Penelitian::create($validated);

        return redirect()->route('penelitian.index')->with('success', 'Data berhasil disimpan');
    }

    public function show(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && !$user->canReviewTriDharma() && $penelitian->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke penelitian ini.');
        }

        $penelitian->load('user', 'verifiedBy', 'publikasi');

        return view('penelitian.show', compact('penelitian'));
    }

    public function edit(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();

        // 1. Cek Permission Input
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Cek Kepemilikan (FIX: Gunakan != bukan !== agar aman tipe data)
        if ($user && $penelitian->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Cek Status Verifikasi (Hanya blokir jika sudah disetujui)
        if (in_array($penelitian->status_verifikasi, ['verified', 'disetujui'])) {
            abort(403, 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        return view('penelitian.edit', compact('penelitian'));
    }

    public function update(Request $request, Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();

        // 1. Cek Permission Input
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Cek Kepemilikan (FIX: Gunakan != bukan !== agar aman tipe data)
        if ($user && $penelitian->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Cek Status Verifikasi (Hanya blokir jika sudah disetujui)
        if (in_array($penelitian->status_verifikasi, ['verified', 'disetujui'])) {
            abort(403, 'Data yang sudah diverifikasi tidak dapat diubah.');
        }

        $validated = $request->validate([
            'judul_penelitian' => 'required|string|max:500',
            'abstrak' => 'nullable|string',
            'jenis' => 'required|in:internal,eksternal,mandiri,hibah_internal,hibah_eksternal,kerjasama',
            'sumber_dana' => 'nullable|string|max:255',
            'anggaran' => 'nullable|numeric|min:0',
            'tahun' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catatan' => 'nullable|string',
            'anggota_peneliti' => 'nullable|array',
            'anggota_peneliti.*.nama' => 'required_with:anggota_peneliti|string',
            'anggota_peneliti.*.nip' => 'nullable|string',
        ]);

        if ($request->has('anggota_peneliti')) {
            $validated['anggota'] = json_encode(array_values($request->anggota_peneliti));
        }

        if ($request->hasFile('file_proposal')) {
            if ($penelitian->file_proposal) {
                Storage::disk('public')->delete($penelitian->file_proposal);
            }
            $originalName = $request->file('file_proposal')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_proposal'] = $request->file('file_proposal')->storeAs('penelitian/proposal', $safeName, 'public');
        }

        if ($request->hasFile('file_laporan')) {
            if ($penelitian->file_laporan) {
                Storage::disk('public')->delete($penelitian->file_laporan);
            }
            $originalName = $request->file('file_laporan')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_laporan'] = $request->file('file_laporan')->storeAs('penelitian/laporan', $safeName, 'public');
        }

        // Reset verifikasi jika status sebelumnya ditolak, agar bisa direview ulang
        if ($penelitian->status_verifikasi === 'rejected' || $penelitian->status_verifikasi === 'ditolak') {
            $validated['status_verifikasi'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
        }

        $penelitian->update($validated);

        return redirect()->route('penelitian.index')->with('success', 'Data penelitian berhasil diperbarui.');
    }

    public function destroy(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();

        // 1. Cek Permission
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Cek Kepemilikan (FIX: Gunakan != bukan !== agar aman tipe data)
        if ($user && $penelitian->user_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // 3. Cek Status Verifikasi
        // Hapus hanya dilarang jika sudah Verified. Pending & Rejected boleh dihapus.
        if (in_array($penelitian->status_verifikasi, ['verified', 'disetujui'])) {
            abort(403, 'Data yang sudah diverifikasi tidak dapat dihapus.');
        }

        if ($penelitian->file_proposal) {
            Storage::disk('public')->delete($penelitian->file_proposal);
        }
        if ($penelitian->file_laporan) {
            Storage::disk('public')->delete($penelitian->file_laporan);
        }

        $penelitian->delete();

        return redirect()->route('penelitian.index')->with('success', 'Data penelitian berhasil dihapus.');
    }

    public function downloadProposal(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canReviewTriDharma())) {
            abort(403, 'Unauthorized action.');
        }

        if (!$penelitian->file_proposal || !Storage::disk('public')->exists($penelitian->file_proposal)) {
            abort(404, 'File not found.');
        }

        $filename = basename($penelitian->file_proposal);
        $originalName = preg_replace('/^\d+_/', '', $filename);

        $filePath = Storage::disk('public')->path($penelitian->file_proposal);
        
        return Response::download($filePath, $originalName);
    }

    public function downloadLaporan(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canReviewTriDharma())) {
            abort(403, 'Unauthorized action.');
        }

        if (!$penelitian->file_laporan || !Storage::disk('public')->exists($penelitian->file_laporan)) {
            abort(404, 'File not found.');
        }

        $filename = basename($penelitian->file_laporan);
        $originalName = preg_replace('/^\d+_/', '', $filename);

        $filePath = Storage::disk('public')->path($penelitian->file_laporan);
        
        return Response::download($filePath, $originalName);
    }

    public function verify(Request $request, Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canVerify())) {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi.');
        }

        $validated = $request->validate([
            'status_verifikasi' => 'required|in:verified,rejected',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        $penelitian->update([
            'status_verifikasi' => $validated['status_verifikasi'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? null,
        ]);

        $status = $validated['status_verifikasi'] === 'verified' ? 'diverifikasi' : 'ditolak';

        return redirect()->back()->with('success', "Penelitian berhasil {$status}.");
    }

    public function bulkDestroy(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->isAdmin())) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data massal.');
        }

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:penelitian,id',
        ]);

        $count = 0;
        $penelitians = Penelitian::whereIn('id', $validated['ids'])->get();

        foreach ($penelitians as $penelitian) {
            if ($penelitian->file_proposal) {
                Storage::disk('public')->delete($penelitian->file_proposal);
            }
            if ($penelitian->file_laporan) {
                Storage::disk('public')->delete($penelitian->file_laporan);
            }
            $penelitian->delete();
            $count++;
        }

        return redirect()->route('penelitian.index')->with('success', "{$count} data penelitian berhasil dihapus.");
    }

    public function emptyTable(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->isAdmin())) {
            abort(403, 'Anda tidak memiliki akses untuk mengosongkan data.');
        }

        $allPenelitian = Penelitian::all();
        foreach ($allPenelitian as $penelitian) {
            if ($penelitian->file_proposal) {
                Storage::disk('public')->delete($penelitian->file_proposal);
            }
            if ($penelitian->file_laporan) {
                Storage::disk('public')->delete($penelitian->file_laporan);
            }
        }

        Schema::disableForeignKeyConstraints();
        Penelitian::truncate();
        Schema::enableForeignKeyConstraints();

        return redirect()->route('penelitian.index')->with('success', 'Semua data penelitian berhasil dihapus.');
    }
}