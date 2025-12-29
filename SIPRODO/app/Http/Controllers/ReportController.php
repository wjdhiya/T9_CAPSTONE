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
        // Get filter parameters - tahun akademik kosong jika semua periode
        $tahun_akademik = $request->get('tahun_akademik');
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all'); // all, penelitian, publikasi, pengmas
        $user_id = $request->get('user_id');

        // Get statistics
        $stats = $this->getStatistics($tahun_akademik, $semester, $user_id);

        // Get users for filter
        $users = User::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get available years
        $years = range(date('Y'), date('Y') - 5);

        return view('reports.index', compact('stats', 'users', 'years', 'tahun_akademik', 'semester', 'jenis', 'user_id'));
    }

    /**
     * Get statistics for reports
     */
    private function getStatistics($tahun_akademik, $semester = null, $user_id = null)
    {
        $stats = [];

        // Penelitian statistics - handle tahun akademik kosong
        $penelitianQuery = $tahun_akademik ? 
            Penelitian::where('tahun_akademik', 'like', $tahun_akademik . '%') :
            Penelitian::rentangTahunAkademik(2022);
        
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

        // Publikasi statistics - menggunakan like pattern untuk handle format "2024/2025"
        $publikasiQuery = Publikasi::where('tahun_akademik', 'like', $tahun_akademik . '%');
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

        // Pengabdian Masyarakat statistics - menggunakan like pattern untuk handle format "2024/2025"
        $pengmasQuery = PengabdianMasyarakat::where('tahun_akademik', 'like', $tahun_akademik . '%');
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
        // Handle tahun akademik - kosongkan jika "Semua Periode"
        $tahun_akademik = $request->get('tahun_akademik');
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all');
        $user_id = $request->get('user_id');

        // Map semester values
        if ($semester == '1') {
            $semester = 'ganjil';
        } elseif ($semester == '2') {
            $semester = 'genap';
        }

        // For now, return a simple CSV
        // TODO: Implement proper Excel export using Laravel Excel package

        $filename = "laporan_{$jenis}_" . ($tahun_akademik ?? 'semua_tahun') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($tahun_akademik, $semester, $jenis, $user_id) {
            $file = fopen('php://output', 'w');

            if ($jenis === 'all' || $jenis === 'penelitian') {
                // Penelitian header
                fputcsv($file, ['LAPORAN PENELITIAN']);
                fputcsv($file, ['Judul', 'Dosen', 'Jenis', 'Tahun Akademik', 'Semester', 'Dana', 'Status', 'Verifikasi']);

                // Handle tahun akademik kosong (semua periode)
                $query = Penelitian::with('user');
                if ($tahun_akademik) {
                    $query->where('tahun_akademik', 'like', $tahun_akademik . '%');
                } else {
                    $query->rentangTahunAkademik(2022); // Semua tahun dari 2022
                }
                
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
                fputcsv($file, ['Judul', 'Penulis', 'Jenis', 'Penerbit', 'Tanggal Terbit', 'Indexing', 'Quartile', 'Verifikasi']);

                // Handle tahun akademik kosong (semua periode)
                $query = Publikasi::with('user');
                if ($tahun_akademik) {
                    $query->where('tahun_akademik', 'like', $tahun_akademik . '%');
                } else {
                    $query->rentangTahunAkademik(2022); // Semua tahun dari 2022
                }
                
                if ($user_id) $query->where('user_id', $user_id);

                foreach ($query->get() as $item) {
                    fputcsv($file, [
                        $item->judul,
                        $item->penulis,
                        $item->jenis,
                        $item->penerbit,
                        $item->tanggal_terbit,
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
                fputcsv($file, ['Judul', 'Dosen', 'Lokasi', 'Mitra', 'Peserta', 'Tahun Akademik', 'Semester', 'Status', 'Verifikasi']);

                // Handle tahun akademik kosong (semua periode)
                $query = PengabdianMasyarakat::with('user');
                if ($tahun_akademik) {
                    $query->where('tahun_akademik', 'like', $tahun_akademik . '%');
                } else {
                    $query->rentangTahunAkademik(2022); // Semua tahun dari 2022
                }
                
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
        // Handle tahun akademik - kosongkan jika "Semua Periode"
        $tahun_akademik = $request->get('tahun_akademik');
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all');
        $user_id = $request->get('user_id');

        $stats = $this->getStatistics($tahun_akademik, $semester, $user_id);

        // Get data
        $data = [];
        
        if ($jenis === 'all' || $jenis === 'penelitian') {
            // Handle tahun akademik kosong (semua periode)
            $query = Penelitian::with('user');
            if ($tahun_akademik) {
                $query->where('tahun_akademik', 'like', $tahun_akademik . '%');
            } else {
                $query->rentangTahunAkademik(2022); // Semua tahun dari 2022
            }
            
            if ($semester) $query->where('semester', $semester);
            if ($user_id) $query->where('user_id', $user_id);
            $data['penelitian'] = $query->get();
        }

        if ($jenis === 'all' || $jenis === 'publikasi') {
            // Handle tahun akademik kosong (semua periode)
            $query = Publikasi::with('user');
            if ($tahun_akademik) {
                $query->where('tahun_akademik', 'like', $tahun_akademik . '%');
            } else {
                $query->rentangTahunAkademik(2022); // Semua tahun dari 2022
            }
            
            if ($user_id) $query->where('user_id', $user_id);
            $data['publikasi'] = $query->get();
        }

        if ($jenis === 'all' || $jenis === 'pengmas') {
            // Handle tahun akademik kosong (semua periode)
            $query = PengabdianMasyarakat::with('user');
            if ($tahun_akademik) {
                $query->where('tahun_akademik', 'like', $tahun_akademik . '%');
            } else {
                $query->rentangTahunAkademik(2022); // Semua tahun dari 2022
            }
            
            if ($semester) $query->where('semester', $semester);
            if ($user_id) $query->where('user_id', $user_id);
            $data['pengmas'] = $query->get();
        }

        // For now, return HTML view
        // TODO: Implement proper PDF export using DomPDF or similar
        return view('reports.pdf', compact('stats', 'data', 'tahun_akademik', 'semester', 'jenis'));
    }

    /**
     * Productivity report
     */
    public function productivity(Request $request)
    {
        $tahun_akademik = $request->get('tahun_akademik', date('Y'));

        // Get all active dosen
        $dosens = User::where('role', 'dosen')
            ->where('is_active', true)
            ->get();

        $productivity = [];

        foreach ($dosens as $dosen) {
            $productivity[] = [
                'dosen' => $dosen,
                'penelitian' => Penelitian::where('user_id', $dosen->id)
                    ->where('tahun_akademik', 'like', $tahun_akademik . '%')
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'publikasi' => Publikasi::where('user_id', $dosen->id)
                    ->where('tahun_akademik', 'like', $tahun_akademik . '%')
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'pengmas' => PengabdianMasyarakat::where('user_id', $dosen->id)
                    ->where('tahun_akademik', 'like', $tahun_akademik . '%')
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

        return view('reports.productivity', compact('productivity', 'years', 'tahun_akademik'));
    }
}

