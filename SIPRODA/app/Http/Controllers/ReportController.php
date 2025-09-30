<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\PengabdianMasyarakat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display reports page
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $tahun = $request->get('tahun', date('Y'));
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all'); // all, penelitian, publikasi, pengmas
        $user_id = $request->get('user_id');

        // Get statistics
        $stats = $this->getStatistics($tahun, $semester, $user_id);

        // Get users for filter
        $users = User::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get available years
        $years = range(date('Y'), date('Y') - 5);

        return view('reports.index', compact('stats', 'users', 'years', 'tahun', 'semester', 'jenis', 'user_id'));
    }

    /**
     * Get statistics for reports
     */
    private function getStatistics($tahun, $semester = null, $user_id = null)
    {
        $stats = [];

        // Penelitian statistics
        $penelitianQuery = Penelitian::whereYear('tanggal_mulai', $tahun);
        if ($semester) {
            $penelitianQuery->where('semester', $semester);
        }
        if ($user_id) {
            $penelitianQuery->where('user_id', $user_id);
        }

        $stats['penelitian'] = [
            'total' => $penelitianQuery->count(),
            'verified' => (clone $penelitianQuery)->where('status_verifikasi', 'verified')->count(),
            'pending' => (clone $penelitianQuery)->where('status_verifikasi', 'pending')->count(),
            'rejected' => (clone $penelitianQuery)->where('status_verifikasi', 'rejected')->count(),
            'by_jenis' => (clone $penelitianQuery)->select('jenis', DB::raw('count(*) as total'))
                ->groupBy('jenis')
                ->pluck('total', 'jenis')
                ->toArray(),
        ];

        // Publikasi statistics
        $publikasiQuery = Publikasi::whereYear('tanggal_publikasi', $tahun);
        if ($user_id) {
            $publikasiQuery->where('user_id', $user_id);
        }

        $stats['publikasi'] = [
            'total' => $publikasiQuery->count(),
            'verified' => (clone $publikasiQuery)->where('status_verifikasi', 'verified')->count(),
            'pending' => (clone $publikasiQuery)->where('status_verifikasi', 'pending')->count(),
            'rejected' => (clone $publikasiQuery)->where('status_verifikasi', 'rejected')->count(),
            'by_jenis' => (clone $publikasiQuery)->select('jenis', DB::raw('count(*) as total'))
                ->groupBy('jenis')
                ->pluck('total', 'jenis')
                ->toArray(),
            'by_indexing' => (clone $publikasiQuery)->select('indexing', DB::raw('count(*) as total'))
                ->whereNotNull('indexing')
                ->groupBy('indexing')
                ->pluck('total', 'indexing')
                ->toArray(),
        ];

        // Pengabdian Masyarakat statistics
        $pengmasQuery = PengabdianMasyarakat::whereYear('tanggal_mulai', $tahun);
        if ($semester) {
            $pengmasQuery->where('semester', $semester);
        }
        if ($user_id) {
            $pengmasQuery->where('user_id', $user_id);
        }

        $stats['pengmas'] = [
            'total' => $pengmasQuery->count(),
            'verified' => (clone $pengmasQuery)->where('status_verifikasi', 'verified')->count(),
            'pending' => (clone $pengmasQuery)->where('status_verifikasi', 'pending')->count(),
            'rejected' => (clone $pengmasQuery)->where('status_verifikasi', 'rejected')->count(),
            'total_peserta' => (clone $pengmasQuery)->sum('jumlah_peserta'),
        ];

        // Productivity ratio
        $totalDosen = User::where('role', 'dosen')->where('is_active', true)->count();
        if ($totalDosen > 0) {
            $stats['ratio'] = [
                'penelitian_per_dosen' => round($stats['penelitian']['total'] / $totalDosen, 2),
                'publikasi_per_dosen' => round($stats['publikasi']['total'] / $totalDosen, 2),
                'pengmas_per_dosen' => round($stats['pengmas']['total'] / $totalDosen, 2),
            ];
        }

        return $stats;
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all');
        $user_id = $request->get('user_id');

        // For now, return a simple CSV
        // TODO: Implement proper Excel export using Laravel Excel package

        $filename = "laporan_{$jenis}_{$tahun}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($tahun, $semester, $jenis, $user_id) {
            $file = fopen('php://output', 'w');

            if ($jenis === 'all' || $jenis === 'penelitian') {
                // Penelitian header
                fputcsv($file, ['LAPORAN PENELITIAN']);
                fputcsv($file, ['Judul', 'Dosen', 'Jenis', 'Tahun', 'Semester', 'Dana', 'Status', 'Verifikasi']);

                $query = Penelitian::with('user')->whereYear('tanggal_mulai', $tahun);
                if ($semester) $query->where('semester', $semester);
                if ($user_id) $query->where('user_id', $user_id);

                foreach ($query->get() as $item) {
                    fputcsv($file, [
                        $item->judul,
                        $item->user->name,
                        $item->jenis,
                        $item->tahun_akademik,
                        $item->semester,
                        $item->dana,
                        $item->status,
                        $item->status_verifikasi,
                    ]);
                }
                fputcsv($file, []); // Empty line
            }

            if ($jenis === 'all' || $jenis === 'publikasi') {
                // Publikasi header
                fputcsv($file, ['LAPORAN PUBLIKASI']);
                fputcsv($file, ['Judul', 'Penulis', 'Jenis', 'Penerbit', 'Tanggal', 'Indexing', 'Quartile', 'Verifikasi']);

                $query = Publikasi::with('user')->whereYear('tanggal_publikasi', $tahun);
                if ($user_id) $query->where('user_id', $user_id);

                foreach ($query->get() as $item) {
                    fputcsv($file, [
                        $item->judul,
                        $item->penulis,
                        $item->jenis,
                        $item->penerbit,
                        $item->tanggal_publikasi,
                        $item->indexing ?? '-',
                        $item->quartile ?? '-',
                        $item->status_verifikasi,
                    ]);
                }
                fputcsv($file, []); // Empty line
            }

            if ($jenis === 'all' || $jenis === 'pengmas') {
                // Pengmas header
                fputcsv($file, ['LAPORAN PENGABDIAN MASYARAKAT']);
                fputcsv($file, ['Judul', 'Dosen', 'Lokasi', 'Mitra', 'Peserta', 'Tahun', 'Semester', 'Status', 'Verifikasi']);

                $query = PengabdianMasyarakat::with('user')->whereYear('tanggal_mulai', $tahun);
                if ($semester) $query->where('semester', $semester);
                if ($user_id) $query->where('user_id', $user_id);

                foreach ($query->get() as $item) {
                    fputcsv($file, [
                        $item->judul,
                        $item->user->name,
                        $item->lokasi,
                        $item->mitra,
                        $item->jumlah_peserta,
                        $item->tahun_akademik,
                        $item->semester,
                        $item->status,
                        $item->status_verifikasi,
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all');
        $user_id = $request->get('user_id');

        $stats = $this->getStatistics($tahun, $semester, $user_id);

        // Get data
        $data = [];
        
        if ($jenis === 'all' || $jenis === 'penelitian') {
            $query = Penelitian::with('user')->whereYear('tanggal_mulai', $tahun);
            if ($semester) $query->where('semester', $semester);
            if ($user_id) $query->where('user_id', $user_id);
            $data['penelitian'] = $query->get();
        }

        if ($jenis === 'all' || $jenis === 'publikasi') {
            $query = Publikasi::with('user')->whereYear('tanggal_publikasi', $tahun);
            if ($user_id) $query->where('user_id', $user_id);
            $data['publikasi'] = $query->get();
        }

        if ($jenis === 'all' || $jenis === 'pengmas') {
            $query = PengabdianMasyarakat::with('user')->whereYear('tanggal_mulai', $tahun);
            if ($semester) $query->where('semester', $semester);
            if ($user_id) $query->where('user_id', $user_id);
            $data['pengmas'] = $query->get();
        }

        // For now, return HTML view
        // TODO: Implement proper PDF export using DomPDF or similar
        return view('reports.pdf', compact('stats', 'data', 'tahun', 'semester', 'jenis'));
    }

    /**
     * Productivity report
     */
    public function productivity(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Get all active dosen
        $dosens = User::where('role', 'dosen')
            ->where('is_active', true)
            ->get();

        $productivity = [];

        foreach ($dosens as $dosen) {
            $productivity[] = [
                'dosen' => $dosen,
                'penelitian' => Penelitian::where('user_id', $dosen->id)
                    ->whereYear('tanggal_mulai', $tahun)
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'publikasi' => Publikasi::where('user_id', $dosen->id)
                    ->whereYear('tanggal_publikasi', $tahun)
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'pengmas' => PengabdianMasyarakat::where('user_id', $dosen->id)
                    ->whereYear('tanggal_mulai', $tahun)
                    ->where('status_verifikasi', 'verified')
                    ->count(),
            ];
        }

        // Sort by total productivity
        usort($productivity, function($a, $b) {
            $totalA = $a['penelitian'] + $a['publikasi'] + $a['pengmas'];
            $totalB = $b['penelitian'] + $b['publikasi'] + $b['pengmas'];
            return $totalB - $totalA;
        });

        $years = range(date('Y'), date('Y') - 5);

        return view('reports.productivity', compact('productivity', 'years', 'tahun'));
    }
}

