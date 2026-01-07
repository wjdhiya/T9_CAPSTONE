<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use App\Models\Penelitian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema; // Tambahkan ini untuk fix truncate

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publikasi::with(['user', 'penelitian', 'verifiedBy']);

        // Filter by user role
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isKaprodi()) {
            User::where('id', $user->id)->update(['kaprodi_seen_publikasi_at' => now()]);
        }

        // Only restrict to user's own data if they are Dosen AND cannot verify (regular Dosen)
        if ($user && $user->isDosen() && !$user->canVerify()) {
            $query->where('user_id', $user->id);
        }

        // Search (Judul, Penerbit, dan Nama Dosen/User)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_publikasi', 'like', '%' . $search . '%')
                    ->orWhere('penerbit', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter by indexing
        if ($request->filled('indexing')) {
            $query->where('indexing', $request->indexing);
        }

        // Filter by status verifikasi
        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        // Get statistics (Global default)
        $stats = [
            'total' => Publikasi::count(),
            'verified' => Publikasi::where('status_verifikasi', 'verified')->count(),
            'pending' => Publikasi::where('status_verifikasi', 'pending')->count(),
            'high_impact' => Publikasi::whereIn('indexing', ['scopus', 'wos'])->count(),
        ];

        // Adjust stats if user is restricted
        if ($user && $user->isDosen() && !$user->canVerify()) {
            $stats = [
                'total' => Publikasi::where('user_id', $user->id)->count(),
                'verified' => Publikasi::where('user_id', $user->id)->where('status_verifikasi', 'verified')->count(),
                'pending' => Publikasi::where('user_id', $user->id)->where('status_verifikasi', 'pending')->count(),
                'high_impact' => Publikasi::where('user_id', $user->id)->whereIn('indexing', ['scopus', 'wos'])->count(),
            ];
        }

        // Support for Show All functionality
        if ($request->query('show_all') === '1') {
            $publikasi = $query->latest()->get();
        } else {
            $publikasi = $query->latest()->paginate(10);
        }

        return view('publikasi.index', compact('publikasi', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan data.');
        }

        // Ambil list penelitian milik user yang sudah verified untuk dikaitkan
        $penelitianList = Penelitian::where('user_id', Auth::id())
            ->where('status_verifikasi', 'verified') // Opsional: hanya penelitian verified yg bisa dikaitkan
            ->get();

        return view('publikasi.create', compact('penelitianList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan data.');
        }

        $validated = $request->validate([
            'judul_publikasi' => 'required|string|max:500',
            'penulis' => 'nullable|array',
            'penulis.*.nama' => 'required_with:penulis|string',
            'penulis.*.nip' => 'nullable|string',
            'jenis' => 'required|in:jurnal,prosiding,buku,book_chapter,paten,hki',
            'penerbit' => 'nullable|string|max:255',
            'tanggal_terbit' => 'nullable|date',
            'issn_isbn' => 'nullable|string|max:50',
            'volume' => 'nullable|string|max:50',
            'nomor' => 'nullable|string|max:50',
            'halaman' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:500',
            'doi' => 'nullable|string|max:255',
            'indexing' => 'nullable|in:scopus,wos,sinta1,sinta2,sinta3,sinta4,sinta5,sinta6,non-indexed',
            'quartile' => 'nullable|in:Q1,Q2,Q3,Q4,non-quartile',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'semester' => 'required|in:ganjil,genap',
            'penelitian_id' => 'nullable|exists:penelitian,id',
            'file_publikasi' => 'nullable|file|mimes:pdf|max:10240',
            'catatan' => 'nullable|string',
        ]);

        $validated['nama_publikasi'] = $validated['judul_publikasi'];

        // Process Penulis JSON
        if ($request->has('penulis')) {
            // Pastikan array re-indexed agar jadi JSON array standard
            $validated['penulis'] = json_encode(array_values($request->penulis));
        }

        $validated['user_id'] = Auth::id();
        $validated['status_verifikasi'] = 'pending';

        // Handle file upload
        if ($request->hasFile('file_publikasi')) {
            $originalName = $request->file('file_publikasi')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_publikasi'] = $request->file('file_publikasi')->storeAs('publikasi', $safeName, 'public');
        }

        Publikasi::create($validated);

        return redirect()->route('publikasi.index')
            ->with('success', 'Publikasi berhasil ditambahkan dan menunggu verifikasi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Publikasi $publikasi)
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Admin dan Reviewer bisa melihat semua data
        // Dosen biasa hanya bisa melihat data miliknya sendiri
        if ($user && !$user->canReviewTriDharma() && $publikasi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke publikasi ini.');
        }

        $publikasi->load(['user', 'penelitian', 'verifiedBy']);

        return view('publikasi.show', compact('publikasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publikasi $publikasi)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // 1. Cek Permission
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
        }
        
        // 2. Cek Kepemilikan
        if ($user && $publikasi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
        }

        // 3. (BARU) Cek Status Verifikasi
        // Jika sudah verified atau rejected (tergantung kebijakan), lock data.
        if (in_array($publikasi->status_verifikasi, ['verified', 'disetujui'])) {
            abort(403, 'Data yang sudah diverifikasi tidak dapat diedit.');
        }

        $penelitianList = Penelitian::where('user_id', Auth::id())
            ->where('status_verifikasi', 'verified')
            ->get();

        return view('publikasi.edit', compact('publikasi', 'penelitianList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publikasi $publikasi)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // 1. Cek Permission
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
        }
        
        // 2. Cek Kepemilikan
        if ($user && $publikasi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
        }

        // 3. (BARU) Cek Status Verifikasi Sebelum Update
        if (in_array($publikasi->status_verifikasi, ['verified', 'disetujui'])) {
            abort(403, 'Data yang sudah diverifikasi tidak dapat diubah.');
        }

        $validated = $request->validate([
            'judul_publikasi' => 'required|string|max:500',
            'penulis' => 'nullable|array',
            'penulis.*.nama' => 'required_with:penulis|string',
            'penulis.*.nip' => 'nullable|string',
            'jenis' => 'required|in:jurnal,prosiding,buku,book_chapter,paten,hki',
            'penerbit' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'issn_isbn' => 'nullable|string|max:50',
            'volume' => 'nullable|string|max:50',
            'nomor' => 'nullable|string|max:50',
            'halaman' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:500',
            'doi' => 'nullable|string|max:255',
            'indexing' => 'nullable|in:scopus,wos,sinta1,sinta2,sinta3,sinta4,sinta5,sinta6,non-indexed',
            'quartile' => 'nullable|in:Q1,Q2,Q3,Q4,non-quartile',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'semester' => 'required|in:ganjil,genap',
            'penelitian_id' => 'nullable|exists:penelitian,id',
            'file_publikasi' => 'nullable|file|mimes:pdf|max:10240',
            'catatan' => 'nullable|string',
        ]);

        $validated['nama_publikasi'] = $validated['judul_publikasi'];

        // Process Penulis
        if ($request->has('penulis')) {
            $validated['penulis'] = json_encode(array_values($request->penulis));
        }

        // Handle file upload
        if ($request->hasFile('file_publikasi')) {
            // Delete old file
            if ($publikasi->file_publikasi) {
                Storage::disk('public')->delete($publikasi->file_publikasi);
            }
            $originalName = $request->file('file_publikasi')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_publikasi'] = $request->file('file_publikasi')->storeAs('publikasi', $safeName, 'public');
        }

        // Reset verification status if data changed (e.g. from rejected -> pending)
        // Jika sebelumnya rejected, kembalikan ke pending agar bisa direview ulang
        if ($publikasi->status_verifikasi === 'rejected' || $publikasi->status_verifikasi === 'ditolak') {
            $validated['status_verifikasi'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
            // Catatan lama opsional mau dihapus atau tidak
        }

        $publikasi->update($validated);

        return redirect()->route('publikasi.index')
            ->with('success', 'Publikasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publikasi $publikasi)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // 1. Cek Permission
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus publikasi ini.');
        }
        
        // 2. Cek Kepemilikan
        if ($user && $publikasi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus publikasi ini.');
        }

        // 3. (BARU) Cek Status Verifikasi
        if (in_array($publikasi->status_verifikasi, ['verified', 'disetujui'])) {
            abort(403, 'Data yang sudah diverifikasi tidak dapat dihapus.');
        }

        // Delete file if exists
        if ($publikasi->file_publikasi) {
            Storage::disk('public')->delete($publikasi->file_publikasi);
        }

        $publikasi->delete();

        return redirect()->route('publikasi.index')
            ->with('success', 'Publikasi berhasil dihapus.');
    }

    /**
     * Verify publikasi (Admin/Kaprodi only)
     */
    public function verify(Request $request, Publikasi $publikasi)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || !$user->canVerify()) {
            abort(403, 'Anda tidak memiliki akses untuk verifikasi.');
        }

        $validated = $request->validate([
            'status_verifikasi' => 'required|in:verified,rejected',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        $publikasi->update([
            'status_verifikasi' => $validated['status_verifikasi'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? null,
        ]);

        $status = $validated['status_verifikasi'] === 'verified' ? 'diverifikasi' : 'ditolak';

        return redirect()->back()
            ->with('success', "Publikasi berhasil {$status}.");
    }

    /**
     * Download publikasi file (Admin/Kaprodi only)
     */
    public function downloadPublikasi(Publikasi $publikasi)
    {
        /** @var User|null $user */
        $user = Auth::user();
        // Hanya yang punya hak review/admin atau pemilik data (jika perlu) yang bisa download
        // Di sini kita pakai canReviewTriDharma (Admin/Kaprodi/Reviewer)
        if (!$user || !$user->canReviewTriDharma()) {
            abort(403, 'Unauthorized action.');
        }

        if (!$publikasi->file_publikasi || !Storage::disk('public')->exists($publikasi->file_publikasi)) {
            abort(404, 'File not found.');
        }

        $filename = basename($publikasi->file_publikasi);
        // Remove timestamp prefix (format: timestamp_filename)
        $originalName = preg_replace('/^\d+_/', '', $filename);

        $filePath = Storage::disk('public')->path($publikasi->file_publikasi);

        return Response::download($filePath, $originalName);
    }

    /**
     * Bulk destroy functionality
     */
    public function bulkDestroy(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        // Hanya Admin yang bisa hapus massal tanpa peduli status
        if (!($user && $user->isAdmin())) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data massal.');
        }

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:publikasi,id',
        ]);

        $count = 0;
        $publikasis = Publikasi::whereIn('id', $validated['ids'])->get();

        foreach ($publikasis as $publikasi) {
            if ($publikasi->file_publikasi) {
                Storage::disk('public')->delete($publikasi->file_publikasi);
            }
            $publikasi->delete();
            $count++;
        }

        return redirect()->route('publikasi.index')->with('success', "{$count} data publikasi berhasil dihapus.");
    }

    /**
     * Empty table functionality
     */
    public function emptyTable(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->isAdmin())) {
            abort(403, 'Anda tidak memiliki akses untuk mengosongkan data.');
        }

        // Delete all files first
        $allPublikasi = Publikasi::all();
        foreach ($allPublikasi as $publikasi) {
            if ($publikasi->file_publikasi) {
                Storage::disk('public')->delete($publikasi->file_publikasi);
            }
        }

        // --- SOLUSI FOREIGN KEY CONSTRAINT ---
        // Matikan sementara foreign key check untuk bypass error truncate
        Schema::disableForeignKeyConstraints();

        Publikasi::truncate();

        // Hidupkan kembali foreign key check
        Schema::enableForeignKeyConstraints();
        // -------------------------------------

        return redirect()->route('publikasi.index')->with('success', 'Semua data publikasi berhasil dihapus.');
    }
}