<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @property Filesystem $storage
 */

class PenelitianController extends Controller
{
    public function index(Request $request)
    {
        $query = Penelitian::with('user', 'verifiedBy');

        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isDosen()) {
            $query->where('user_id', $user->id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('abstrak', 'like', "%{$search}%");
            });
        }

        if ($request->has('tahun_akademik')) {
            $query->where('tahun_akademik', 'like', $request->tahun_akademik . '%');
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $penelitian = $query->latest()->paginate(10);

        return view('penelitian.index', compact('penelitian'));
    }

    public function create()
    {
        return view('penelitian.create');
    }

    public function store(Request $request)
    {
        

        $validated = $request->validate([
            'judul' => 'required|string|max:500',
            'abstrak' => 'nullable|string',
            'jenis' => 'required|in:internal,eksternal,mandiri,hibah_internal,hibah_eksternal,kerjasama',
            'sumber_dana' => 'nullable|string|max:255',
            'dana' => 'nullable|numeric|min:0',
            'tahun_akademik' => 'required|string|max:20',
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

        $validated['user_id'] = Auth::id();
        $validated['status_verifikasi'] = 'pending';

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
        if ($user && $user->isDosen() && $penelitian->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $penelitian->load('user', 'verifiedBy', 'publikasi');

        return view('penelitian.show', compact('penelitian'));
    }

    public function edit(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->isDosen() && $penelitian->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('penelitian.edit', compact('penelitian'));
    }

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
            'tahun_akademik' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:proposal,berjalan,selesai,ditolak',
            'file_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_laporan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'catatan' => 'nullable|string',
        ]);

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

    public function destroy(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->isDosen() && $penelitian->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

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
     * Download proposal file (Admin/Kaprodi only)
     * 
     * @param Penelitian $penelitian
     * @return BinaryFileResponse
     * 
     * @method BinaryFileResponse download(string $path, string|null $name = null, array $headers = [])
     */
    public function downloadProposal(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canVerify())) {
            abort(403, 'Unauthorized action.');
        }

        if (!$penelitian->file_proposal || !Storage::disk('public')->exists($penelitian->file_proposal)) {
            abort(404, 'File not found.');
        }

        $filename = basename($penelitian->file_proposal);
        // Remove timestamp prefix (format: timestamp_filename)
        $originalName = preg_replace('/^\d+_/', '', $filename);
        
        $filePath = Storage::disk('public')->path($penelitian->file_proposal);
        /** @phpstan-ignore-next-line */
        return Response::download($filePath, $originalName);
    }

    /**
     * Download laporan file (Admin/Kaprodi only)
     * 
     * @param Penelitian $penelitian
     * @return BinaryFileResponse
     * 
     * @method BinaryFileResponse download(string $path, string|null $name = null, array $headers = [])
     */
    public function downloadLaporan(Penelitian $penelitian)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->canVerify())) {
            abort(403, 'Unauthorized action.');
        }

        if (!$penelitian->file_laporan || !Storage::disk('public')->exists($penelitian->file_laporan)) {
            abort(404, 'File not found.');
        }

        $filename = basename($penelitian->file_laporan);
        // Remove timestamp prefix (format: timestamp_filename)
        $originalName = preg_replace('/^\d+_/', '', $filename);
        
        $filePath = Storage::disk('public')->path($penelitian->file_laporan);
        /** @phpstan-ignore-next-line */
        return Response::download($filePath, $originalName);
    }

    /**
     * Verify penelitian (Admin/Kaprodi only)
     */
    public function verify(Request $request, Penelitian $penelitian)
    {
        // Authorization check
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

        return redirect()->back()
            ->with('success', "Penelitian berhasil {$status}.");
    }
}
