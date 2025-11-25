<?php

namespace App\Http\Controllers;

use App\Models\PengabdianMasyarakat;
use App\Models\User; // already present
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PengabdianMasyarakatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PengabdianMasyarakat::with(['user', 'verifiedBy']);

        // Filter by user role
        /** @var User|null $user */
        $user = Auth::user(); // <--- ensure typed
        if ($user && $user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%')
                  ->orWhere('mitra', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by status verifikasi
        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $pengmas = $query->latest()->paginate(10);

        return view('pengmas.index', compact('pengmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengmas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'abstrak' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:1',
            'tahun_akademik' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'dana' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'anggota' => 'nullable|array',
            'anggota.*' => 'string|max:255',
            'mahasiswa_terlibat' => 'nullable|array',
            'mahasiswa_terlibat.*' => 'string|max:255',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:20480',
            'catatan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status_verifikasi'] = 'pending';

        // Convert arrays to JSON
        if (isset($validated['anggota'])) {
            $validated['anggota'] = json_encode(array_filter($validated['anggota']));
        }
        if (isset($validated['mahasiswa_terlibat'])) {
            $validated['mahasiswa_terlibat'] = json_encode(array_filter($validated['mahasiswa_terlibat']));
        }

        // Handle file uploads
        if ($request->hasFile('file_proposal')) {
            $validated['file_proposal'] = $request->file('file_proposal')
                ->store('pengmas/proposal', 'public');
        }

        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')
                ->store('pengmas/laporan', 'public');
        }

        if ($request->hasFile('file_dokumentasi')) {
            $validated['file_dokumentasi'] = $request->file('file_dokumentasi')
                ->store('pengmas/dokumentasi', 'public');
        }

        PengabdianMasyarakat::create($validated);

        return redirect()->route('pengmas.index')
            ->with('success', 'Pengabdian Masyarakat berhasil ditambahkan dan menunggu verifikasi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user(); // <--- added/ensure typed
        if ($user && $user->isDosen() && $pengma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengabdian masyarakat ini.');
        }

        $pengma->load(['user', 'verifiedBy']);

        return view('pengmas.show', compact('pengma'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user(); // <--- added/ensure typed
        if ($user && $user->isDosen() && $pengma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengabdian masyarakat ini.');
        }

        return view('pengmas.edit', compact('pengma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user(); // <--- added/ensure typed
        if ($user && $user->isDosen() && $pengma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengabdian masyarakat ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'abstrak' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:1',
            'tahun_akademik' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'dana' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            'anggota' => 'nullable|array',
            'anggota.*' => 'string|max:255',
            'mahasiswa_terlibat' => 'nullable|array',
            'mahasiswa_terlibat.*' => 'string|max:255',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:20480',
            'catatan' => 'nullable|string',
        ]);

        // Convert arrays to JSON
        if (isset($validated['anggota'])) {
            $validated['anggota'] = json_encode(array_filter($validated['anggota']));
        }
        if (isset($validated['mahasiswa_terlibat'])) {
            $validated['mahasiswa_terlibat'] = json_encode(array_filter($validated['mahasiswa_terlibat']));
        }

        // Handle file uploads
        if ($request->hasFile('file_proposal')) {
            if ($pengma->file_proposal) {
                Storage::disk('public')->delete($pengma->file_proposal);
            }
            $validated['file_proposal'] = $request->file('file_proposal')
                ->store('pengmas/proposal', 'public');
        }

        if ($request->hasFile('file_laporan')) {
            if ($pengma->file_laporan) {
                Storage::disk('public')->delete($pengma->file_laporan);
            }
            $validated['file_laporan'] = $request->file('file_laporan')
                ->store('pengmas/laporan', 'public');
        }

        if ($request->hasFile('file_dokumentasi')) {
            if ($pengma->file_dokumentasi) {
                Storage::disk('public')->delete($pengma->file_dokumentasi);
            }
            $validated['file_dokumentasi'] = $request->file('file_dokumentasi')
                ->store('pengmas/dokumentasi', 'public');
        }

        // Reset verification status if data changed
        if ($pengma->status_verifikasi === 'verified') {
            $validated['status_verifikasi'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
            $validated['catatan_verifikasi'] = null;
        }

        $pengma->update($validated);

        return redirect()->route('pengmas.index')
            ->with('success', 'Pengabdian Masyarakat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user(); // <--- added/ensure typed
        if ($user && $user->isDosen() && $pengma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pengabdian masyarakat ini.');
        }

        // Delete files if exist
        if ($pengma->file_proposal) {
            Storage::disk('public')->delete($pengma->file_proposal);
        }
        if ($pengma->file_laporan) {
            Storage::disk('public')->delete($pengma->file_laporan);
        }
        if ($pengma->file_dokumentasi) {
            Storage::disk('public')->delete($pengma->file_dokumentasi);
        }

        $pengma->delete();

        return redirect()->route('pengmas.index')
            ->with('success', 'Pengabdian Masyarakat berhasil dihapus.');
    }

    /**
     * Verify pengabdian masyarakat (Admin/Kaprodi only)
     */
    public function verify(Request $request, PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user(); // <--- added/ensure typed
        if (!($user && $user->canVerify())) {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi.');
        }

        $validated = $request->validate([
            'status_verifikasi' => 'required|in:verified,rejected',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        $pengma->update([
            'status_verifikasi' => $validated['status_verifikasi'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? null,
        ]);

        $status = $validated['status_verifikasi'] === 'verified' ? 'diverifikasi' : 'ditolak';

        return redirect()->back()
            ->with('success', "Pengabdian Masyarakat berhasil {$status}.");
    }
}

