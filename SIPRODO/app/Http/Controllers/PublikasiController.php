<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class PublikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publikasi::with(['user', 'penelitian', 'verifiedBy']);

        // Filter by user role
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        // Search (Judul, Penulis, Penerbit, dan Nama Dosen/User)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('penulis', 'like', '%' . $search . '%') // Pencarian Penulis (Manual input)
                  ->orWhere('penerbit', 'like', '%' . $search . '%')
                  // Tambahan: Cari berdasarkan nama user (Dosen pemilik data)
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by year
        if ($request->filled('tahun_akademik')) {
            $query->where('tahun_akademik', $request->tahun_akademik);
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

        $publikasi = $query->latest()->paginate(10);

        // Get statistics
        $stats = [
            'total' => Publikasi::count(),
            'verified' => Publikasi::where('status_verifikasi', 'verified')->count(),
            'pending' => Publikasi::where('status_verifikasi', 'pending')->count(),
            'high_impact' => Publikasi::whereIn('indexing', ['scopus', 'wos'])->count(),
        ];

        return view('publikasi.index', compact('publikasi', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan data.');
        }

        $penelitianList = Penelitian::where('user_id', Auth::id())
            ->where('status_verifikasi', 'verified')
            ->get();

        return view('publikasi.create', compact('penelitianList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan data.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'penulis' => 'nullable|string',
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
            'tahun_akademik' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'semester' => 'required|in:ganjil,genap',
            'penelitian_id' => 'nullable|exists:penelitian,id',
            'file_publikasi' => 'nullable|file|mimes:pdf|max:10240',
            'catatan' => 'nullable|string',
        ]);

        $validated['nama_publikasi'] = $validated['judul'];

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
        // Authorization check
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->isDosen() && $publikasi->user_id !== $user->id) {
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
        // Authorization check
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
        }
        if ($user && $publikasi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
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
        // Authorization check
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
        }
        if ($user && $publikasi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit publikasi ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'penulis' => 'required|string|max:500',
            'jenis' => 'required|in:jurnal,prosiding,buku,paten,hki',
            'penerbit' => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'issn_isbn' => 'nullable|string|max:50',
            'volume' => 'nullable|string|max:50',
            'nomor' => 'nullable|string|max:50',
            'halaman' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:500',
            'doi' => 'nullable|string|max:255',
            'indexing' => 'nullable|in:scopus,wos,sinta_1,sinta_2,sinta_3,sinta_4,sinta_5,sinta_6,none',
            'quartile' => 'nullable|in:q1,q2,q3,q4',
            'penelitian_id' => 'nullable|exists:penelitian,id',
            'file_publikasi' => 'nullable|file|mimes:pdf|max:10240',
            'catatan' => 'nullable|string',
        ]);

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

        // Reset verification status if data changed
        if ($publikasi->status_verifikasi === 'verified') {
            $validated['status_verifikasi'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
            $validated['catatan_verifikasi'] = null;
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
        // Authorization check
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus publikasi ini.');
        }
        if ($user && $publikasi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus publikasi ini.');
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
        // Authorization check
        /** @var \App\Models\User|null $user */
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
     * * @param Publikasi $publikasi
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadPublikasi(Publikasi $publikasi)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
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
}