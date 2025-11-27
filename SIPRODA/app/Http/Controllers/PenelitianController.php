<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenelitianController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'abstrak' => 'nullable|string',
            'jenis' => 'required|in:internal,eksternal,mandiri,hibah_internal,hibah_eksternal,kerjasama',
            'sumber_dana' => 'nullable|string|max:255',
            'dana' => 'nullable|numeric|min:0',
            'tahun_akademik' => 'required|string|max:20', // terima string apa adanya
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catatan' => 'nullable|string',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // set owner + verification
        $validated['user_id'] = Auth::id();
        $validated['status_verifikasi'] = 'pending';

        // handle file uploads
        if ($request->hasFile('file_proposal')) {
            $validated['file_proposal'] = $request->file('file_proposal')->store('penelitian/proposal', 'public');
        }
        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')->store('penelitian/laporan', 'public');
        }

        \App\Models\Penelitian::create($validated);

        return redirect()->route('penelitian.index')->with('success', 'Data berhasil disimpan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->isDosen() && $penelitian->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'abstrak' => 'nullable|string',
            'jenis' => 'required|in:internal,eksternal,mandiri,hibah_internal,hibah_eksternal,kerjasama',
            'sumber_dana' => 'nullable|string|max:255',
            'dana' => 'nullable|numeric|min:0',
            'tahun_akademik' => 'required|string|max:20', // ubah ke string agar terima "2025/2026"
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catatan' => 'nullable|string',
        ]);
        
        // Handle file uploads
        if ($request->hasFile('file_proposal')) {
            if ($penelitian->file_proposal) {
                Storage::disk('public')->delete($penelitian->file_proposal);
            }
            $validated['file_proposal'] = $request->file('file_proposal')
                ->store('penelitian/proposal', 'public');
        }
        
        if ($request->hasFile('file_laporan')) {
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
}