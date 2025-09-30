<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenelitianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penelitian::with('user', 'verifiedBy');
        
        // Filter by user role
        if (auth()->user()->isDosen()) {
            $query->where('user_id', auth()->id());
        }
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('abstrak', 'like', "%{$search}%");
            });
        }
        
        // Filter by year
        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        
        // Filter by semester
        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by verification status
        if ($request->has('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }
        
        $penelitian = $query->latest()->paginate(10);
        
        return view('penelitian.index', compact('penelitian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penelitian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'abstrak' => 'nullable|string',
            'jenis' => 'required|in:internal,eksternal,mandiri',
            'sumber_dana' => 'nullable|string|max:255',
            'dana' => 'nullable|numeric|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'anggota' => 'nullable|array',
            'anggota.*' => 'string',
            'mahasiswa_terlibat' => 'nullable|array',
            'mahasiswa_terlibat.*' => 'string',
            'catatan' => 'nullable|string',
        ]);
        
        // Handle file uploads
        if ($request->hasFile('file_proposal')) {
            $validated['file_proposal'] = $request->file('file_proposal')
                ->store('penelitian/proposal', 'public');
        }
        
        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')
                ->store('penelitian/laporan', 'public');
        }
        
        // Set user_id
        $validated['user_id'] = auth()->id();
        
        // Set default verification status
        $validated['status_verifikasi'] = 'pending';
        
        Penelitian::create($validated);
        
        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penelitian $penelitian)
    {
        // Check authorization
        if (auth()->user()->isDosen() && $penelitian->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $penelitian->load('user', 'verifiedBy', 'publikasi');
        
        return view('penelitian.show', compact('penelitian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penelitian $penelitian)
    {
        // Check authorization
        if (auth()->user()->isDosen() && $penelitian->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('penelitian.edit', compact('penelitian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penelitian $penelitian)
    {
        // Check authorization
        if (auth()->user()->isDosen() && $penelitian->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'abstrak' => 'nullable|string',
            'jenis' => 'required|in:internal,eksternal,mandiri',
            'sumber_dana' => 'nullable|string|max:255',
            'dana' => 'nullable|numeric|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'anggota' => 'nullable|array',
            'anggota.*' => 'string',
            'mahasiswa_terlibat' => 'nullable|array',
            'mahasiswa_terlibat.*' => 'string',
            'catatan' => 'nullable|string',
        ]);
        
        // Handle file uploads
        if ($request->hasFile('file_proposal')) {
            // Delete old file
            if ($penelitian->file_proposal) {
                Storage::disk('public')->delete($penelitian->file_proposal);
            }
            $validated['file_proposal'] = $request->file('file_proposal')
                ->store('penelitian/proposal', 'public');
        }
        
        if ($request->hasFile('file_laporan')) {
            // Delete old file
            if ($penelitian->file_laporan) {
                Storage::disk('public')->delete($penelitian->file_laporan);
            }
            $validated['file_laporan'] = $request->file('file_laporan')
                ->store('penelitian/laporan', 'public');
        }
        
        // Reset verification if data changed
        if ($penelitian->status_verifikasi === 'verified') {
            $validated['status_verifikasi'] = 'pending';
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
            $validated['catatan_verifikasi'] = null;
        }
        
        $penelitian->update($validated);
        
        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penelitian $penelitian)
    {
        // Check authorization
        if (auth()->user()->isDosen() && $penelitian->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete files
        if ($penelitian->file_proposal) {
            Storage::disk('public')->delete($penelitian->file_proposal);
        }
        if ($penelitian->file_laporan) {
            Storage::disk('public')->delete($penelitian->file_laporan);
        }
        
        $penelitian->delete();
        
        return redirect()->route('penelitian.index')
            ->with('success', 'Data penelitian berhasil dihapus.');
    }
    
    /**
     * Verify penelitian (Kaprodi/Admin only)
     */
    public function verify(Request $request, Penelitian $penelitian)
    {
        // Check authorization
        if (!auth()->user()->canVerify()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:verified,rejected',
            'catatan_verifikasi' => 'nullable|string',
        ]);
        
        $validated['verified_by'] = auth()->id();
        $validated['verified_at'] = now();
        
        $penelitian->update($validated);
        
        return redirect()->back()
            ->with('success', 'Status verifikasi berhasil diperbarui.');
    }
}

