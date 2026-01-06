<?php

namespace App\Http\Controllers;

use App\Models\PengabdianMasyarakat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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
        $user = Auth::user();
        if ($user && $user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        // Search (Judul, Lokasi, Mitra, dan Nama Dosen)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('skema', 'like', '%' . $search . '%')
                  ->orWhere('mitra', 'like', '%' . $search . '%')
                  // Cari berdasarkan nama user (dosen pengusul)
                  ->orWhereHas('user', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->where('tahun', 'like', $request->tahun . '%');
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
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan data.');
        }

        return view('pengmas.create');
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

        // 1. VALIDASI
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            
            // Terima input 'deskripsi' atau 'abstrak'
            'deskripsi' => 'nullable|string', 
            'abstrak'   => 'nullable|string',

            'skema' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:1',
            'tahun' => 'required|string|max:20',
            'semester' => 'required|string', 
            
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'dana' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            
            // Validasi Anggota (Dosen)
            'anggota' => 'nullable|array',
            'anggota.*' => 'nullable|string|max:255',
            'dosen_nip' => 'nullable|array',
            'dosen_nip.*' => 'nullable|string|max:50',
            
            // Validasi Mahasiswa (Input form bernama 'mahasiswa')
            'mahasiswa' => 'nullable|array',
            'mahasiswa.*' => 'nullable|string|max:255',
            'mahasiswa_nim' => 'nullable|array',
            'mahasiswa_nim.*' => 'nullable|string|max:50',
            
            'status' => 'required|string', 
            
            'file_proposal' => 'nullable|file|mimes:pdf|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:20480',
            'catatan' => 'nullable|string',
        ]);

        // 2. MAPPING DATA
        
        // Mapping deskripsi ke abstrak jika diperlukan
        if (isset($validated['deskripsi']) && !isset($validated['abstrak'])) {
            $validated['abstrak'] = $validated['deskripsi'];
        }
        unset($validated['deskripsi']);

        // Default abstrak
        if (empty($validated['abstrak'])) {
            $validated['abstrak'] = '-'; 
        }

        // Format data
        $validated['semester'] = strtolower($validated['semester']); 
        $validated['status'] = strtolower($validated['status']);    

        $validated['user_id'] = Auth::id();
        $validated['status_verifikasi'] = 'pending';

        // --- PERBAIKAN PENTING DI SINI ---
        
        // 1. Proses Anggota (Dosen)
        $anggotaInput = $validated['anggota'] ?? [];
        $anggotaClean = array_values(array_filter($anggotaInput, fn($v) => !empty($v)));
        $validated['anggota'] = json_encode($anggotaClean);
        
        // 1b. Proses NIP Dosen
        $nipInput = $validated['dosen_nip'] ?? [];
        $nipClean = array_values(array_filter($nipInput, fn($v) => !empty($v)));
        $validated['dosen_nip'] = json_encode($nipClean);

        // 2. Proses Mahasiswa
        // Kita simpan ke DUA key: 'mahasiswa' DAN 'mahasiswa_terlibat'.
        // Ini memastikan data tersimpan terlepas dari nama kolom mana yang dipakai di database/model Anda.
        $mahasiswaInput = $validated['mahasiswa'] ?? [];
        $mahasiswaClean = array_values(array_filter($mahasiswaInput, fn($v) => !empty($v)));
        $mahasiswaJson  = json_encode($mahasiswaClean);
        
        $validated['mahasiswa'] = $mahasiswaJson;           // Untuk kolom 'mahasiswa'
        $validated['mahasiswa_terlibat'] = $mahasiswaJson;  // Untuk kolom 'mahasiswa_terlibat' (legacy support)
        
        // 2b. Proses NIM Mahasiswa
        $nimInput = $validated['mahasiswa_nim'] ?? [];
        $nimClean = array_values(array_filter($nimInput, fn($v) => !empty($v)));
        $validated['mahasiswa_nim'] = json_encode($nimClean);

        // Handle file uploads
        if ($request->hasFile('file_proposal')) {
            $originalName = $request->file('file_proposal')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_proposal'] = $request->file('file_proposal')->storeAs('pengmas/proposal', $safeName, 'public');
        }

        if ($request->hasFile('file_laporan')) {
            $originalName = $request->file('file_laporan')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_laporan'] = $request->file('file_laporan')->storeAs('pengmas/laporan', $safeName, 'public');
        }

        if ($request->hasFile('file_dokumentasi')) {
            $originalName = $request->file('file_dokumentasi')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_dokumentasi'] = $request->file('file_dokumentasi')->storeAs('pengmas/dokumentasi', $safeName, 'public');
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
        $user = Auth::user();
        if ($user && $user->isDosen() && $pengma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke pengabdian masyarakat ini.');
        }

        $pengma->load(['user', 'verifiedBy']);

        return view('pengmas.show', [
            'pengabdianMasyarakat' => $pengma
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengabdian masyarakat ini.');
        }
        if ($user && $pengma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengabdian masyarakat ini.');
        }

        return view('pengmas.edit', [
            'pengabdianMasyarakat' => $pengma
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengabdian masyarakat ini.');
        }
        if ($user && $pengma->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengabdian masyarakat ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'deskripsi' => 'nullable|string', 
            'abstrak'   => 'nullable|string',
            'skema' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
            'jumlah_peserta' => 'required|integer|min:1',
            'tahun' => 'required|string|max:20',
            'semester' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'dana' => 'nullable|numeric|min:0',
            'sumber_dana' => 'nullable|string|max:255',
            
            // Validasi Anggota
            'anggota' => 'nullable|array',
            'anggota.*' => 'nullable|string|max:255',
            'dosen_nip' => 'nullable|array',
            'dosen_nip.*' => 'nullable|string|max:50',
            
            // Validasi Mahasiswa
            'mahasiswa' => 'nullable|array',
            'mahasiswa.*' => 'nullable|string|max:255',
            'mahasiswa_nim' => 'nullable|array',
            'mahasiswa_nim.*' => 'nullable|string|max:50',
            
            'status' => 'required|string',
            'file_proposal' => 'nullable|file|mimes:pdf|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
            'file_dokumentasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:20480',
            'catatan' => 'nullable|string',
        ]);

        // MAPPING DATA UPDATE
        if (isset($validated['deskripsi']) && !isset($validated['abstrak'])) {
            $validated['abstrak'] = $validated['deskripsi'];
        }
        unset($validated['deskripsi']);
        
        $validated['semester'] = strtolower($validated['semester']);
        $validated['status'] = strtolower($validated['status']);

        // --- PERBAIKAN PENTING DI SINI ---

        // 1. Proses Anggota (Dosen)
        $anggotaInput = $validated['anggota'] ?? [];
        $anggotaClean = array_values(array_filter($anggotaInput, fn($v) => !empty($v)));
        $validated['anggota'] = json_encode($anggotaClean);
        
        // 1b. Proses NIP Dosen
        $nipInput = $validated['dosen_nip'] ?? [];
        $nipClean = array_values(array_filter($nipInput, fn($v) => !empty($v)));
        $validated['dosen_nip'] = json_encode($nipClean);

        // 2. Proses Mahasiswa
        // Simpan ke dua key untuk keamanan kompatibilitas
        $mahasiswaInput = $validated['mahasiswa'] ?? [];
        $mahasiswaClean = array_values(array_filter($mahasiswaInput, fn($v) => !empty($v)));
        $mahasiswaJson  = json_encode($mahasiswaClean);
        
        $validated['mahasiswa'] = $mahasiswaJson;
        $validated['mahasiswa_terlibat'] = $mahasiswaJson;
        
        // 2b. Proses NIM Mahasiswa
        $nimInput = $validated['mahasiswa_nim'] ?? [];
        $nimClean = array_values(array_filter($nimInput, fn($v) => !empty($v)));
        $validated['mahasiswa_nim'] = json_encode($nimClean);

        // Handle file uploads
        if ($request->hasFile('file_proposal')) {
            if ($pengma->file_proposal) {
                Storage::disk('public')->delete($pengma->file_proposal);
            }
            $originalName = $request->file('file_proposal')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_proposal'] = $request->file('file_proposal')->storeAs('pengmas/proposal', $safeName, 'public');
        }

        if ($request->hasFile('file_laporan')) {
            if ($pengma->file_laporan) {
                Storage::disk('public')->delete($pengma->file_laporan);
            }
            $originalName = $request->file('file_laporan')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_laporan'] = $request->file('file_laporan')->storeAs('pengmas/laporan', $safeName, 'public');
        }

        if ($request->hasFile('file_dokumentasi')) {
            if ($pengma->file_dokumentasi) {
                Storage::disk('public')->delete($pengma->file_dokumentasi);
            }
            $originalName = $request->file('file_dokumentasi')->getClientOriginalName();
            $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
            $validated['file_dokumentasi'] = $request->file('file_dokumentasi')->storeAs('pengmas/dokumentasi', $safeName, 'public');
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
        $user = Auth::user();
        if (!($user && $user->canInputTriDharma())) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pengabdian masyarakat ini.');
        }
        if ($user && $pengma->user_id !== $user->id) {
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
        $user = Auth::user();
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

    /**
     * Download/Preview proposal file
     */
    public function downloadProposal(PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // Admin/Kaprodi can download, others can only preview
        $canDownload = $user && $user->canReviewTriDharma();
        
        if (!$pengma->file_proposal || !Storage::disk('public')->exists($pengma->file_proposal)) {
            abort(404, 'File not found.');
        }

        $filePath = Storage::disk('public')->path($pengma->file_proposal);
        $filename = basename($pengma->file_proposal);
        $originalName = preg_replace('/^\d+_/', '', $filename);
        
        // If admin/kaprodi, force download. Otherwise, inline preview
        if ($canDownload) {
            return Response::download($filePath, $originalName);
        } else {
            return Response::file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $originalName . '"'
            ]);
        }
    }

    /**
     * Download/Preview laporan file
     */
    public function downloadLaporan(PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // Admin/Kaprodi can download, others can only preview
        $canDownload = $user && $user->canReviewTriDharma();
        
        if (!$pengma->file_laporan || !Storage::disk('public')->exists($pengma->file_laporan)) {
            abort(404, 'File not found.');
        }

        $filePath = Storage::disk('public')->path($pengma->file_laporan);
        $filename = basename($pengma->file_laporan);
        $originalName = preg_replace('/^\d+_/', '', $filename);
        
        // If admin/kaprodi, force download. Otherwise, inline preview
        if ($canDownload) {
            return Response::download($filePath, $originalName);
        } else {
            return Response::file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $originalName . '"'
            ]);
        }
    }

    /**
     * Download/Preview dokumentasi file
     */
    public function downloadDokumentasi(PengabdianMasyarakat $pengma)
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        // Admin/Kaprodi can download, others can only preview
        $canDownload = $user && $user->canReviewTriDharma();
        
        if (!$pengma->file_dokumentasi || !Storage::disk('public')->exists($pengma->file_dokumentasi)) {
            abort(404, 'File not found.');
        }

        $filePath = Storage::disk('public')->path($pengma->file_dokumentasi);
        $filename = basename($pengma->file_dokumentasi);
        $originalName = preg_replace('/^\d+_/', '', $filename);
        $mimeType = mime_content_type($filePath);
        
        // If admin/kaprodi, force download. Otherwise, inline preview for supported types
        if ($canDownload) {
            return Response::download($filePath, $originalName);
        } else {
            // For images and PDFs, show inline. For others, download
            if (str_starts_with($mimeType, 'image/') || $mimeType === 'application/pdf') {
                return Response::file($filePath, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . $originalName . '"'
                ]);
            } else {
                return Response::download($filePath, $originalName);
            }
        }
    }
}