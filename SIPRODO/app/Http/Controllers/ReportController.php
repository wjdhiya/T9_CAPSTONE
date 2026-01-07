<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\PengabdianMasyarakat;
use App\Models\User;
use App\Exports\TriDharmaExport;
use App\Exports\MultiSheetTriDharmaExport;
use Maatwebsite\Excel\Facades\Excel;
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
        $tahun = $request->get('tahun');
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

        // Penelitian statistics - handle tahun akademik kosong
        $penelitianQuery = $tahun ?
            Penelitian::where('tahun', 'like', $tahun . '%') :
            Penelitian::rentangTahun(2022);

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
        $publikasiQuery = Publikasi::where('tahun', 'like', $tahun . '%');
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
        $pengmasQuery = PengabdianMasyarakat::where('tahun', 'like', $tahun . '%');
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
        $tahun = $request->get('tahun');
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all');
        $user_id = $request->get('user_id');

        if ($semester == '1') {
            $semester = 'ganjil';
        } elseif ($semester == '2') {
            $semester = 'genap';
        }

        $filename = "laporan_{$jenis}_" . ($tahun ?? 'semua_tahun') . ".xlsx";

        \Illuminate\Support\Facades\Log::info("Exporting Excel: Jenis={$jenis}, Tahun={$tahun}, Semester={$semester}, UserID={$user_id}");

        if ($jenis === 'all') {
            $export = new MultiSheetTriDharmaExport();

            $penelitianParams = $this->getPenelitianExportData($tahun, $semester, $user_id);
            \Illuminate\Support\Facades\Log::info("Penelitian Count: " . $penelitianParams['data']->count());

            $export->addSheet(new TriDharmaExport(
                $penelitianParams['data'],
                'penelitian',
                ['No', 'Judul Penelitian', 'Nama Dosen', 'NIP', 'Jenis', 'Tahun', 'Semester', 'Sumber Dana', 'Anggaran', 'Tanggal Mulai', 'Tanggal Selesai', 'Status', 'Status Verifikasi'],
                $penelitianParams['merges']
            ));

            $publikasiParams = $this->getPublikasiExportData($tahun, $semester, $user_id);
            \Illuminate\Support\Facades\Log::info("Publikasi Count: " . $publikasiParams['data']->count());

            $export->addSheet(new TriDharmaExport(
                $publikasiParams['data'],
                'publikasi',
                ['No', 'Judul Publikasi', 'Nama Dosen', 'NIP', 'Jenis', 'Nama Jurnal/Penerbit', 'Penerbit', 'ISSN/ISBN', 'Volume', 'Nomor', 'Halaman', 'Tanggal Terbit', 'Indexing', 'Quartile', 'DOI', 'URL', 'Tahun', 'Semester', 'Status Verifikasi'],
                $publikasiParams['merges']
            ));

            $pengmasParams = $this->getPengmasExportData($tahun, $semester, $user_id);
            \Illuminate\Support\Facades\Log::info("Pengmas Count: " . $pengmasParams['data']->count());

            $export->addSheet(new TriDharmaExport(
                $pengmasParams['data'],
                'pengmas',
                ['No', 'Judul PKM', 'Nama Dosen', 'NIP', 'Jenis Hibah', 'Skema', 'Mitra', 'Jumlah Peserta', 'Tahun', 'Semester', 'Sumber Dana', 'Anggaran', 'Tanggal Mulai', 'Tanggal Selesai', 'Tim Abdimas', 'NIP Anggota', 'Anggota Mahasiswa', 'NIM Mahasiswa', 'SDG', 'Status', 'Status Verifikasi'],
                $pengmasParams['merges']
            ));

            return Excel::download($export, $filename);
        }

        if ($jenis === 'penelitian') {
            $params = $this->getPenelitianExportData($tahun, $semester, $user_id);
            $headings = ['No', 'Judul Penelitian', 'Nama Dosen', 'NIP', 'Jenis', 'Tahun', 'Semester', 'Sumber Dana', 'Anggaran', 'Tanggal Mulai', 'Tanggal Selesai', 'Status', 'Status Verifikasi'];
            return Excel::download(new TriDharmaExport($params['data'], 'penelitian', $headings, $params['merges']), $filename);
        }

        if ($jenis === 'publikasi') {
            $params = $this->getPublikasiExportData($tahun, $semester, $user_id);
            $headings = ['No', 'Judul Publikasi', 'Nama Dosen', 'NIP', 'Jenis', 'Nama Jurnal/Penerbit', 'Penerbit', 'ISSN/ISBN', 'Volume', 'Nomor', 'Halaman', 'Tanggal Terbit', 'Indexing', 'Quartile', 'DOI', 'URL', 'Tahun', 'Semester', 'Status Verifikasi'];
            return Excel::download(new TriDharmaExport($params['data'], 'publikasi', $headings, $params['merges']), $filename);
        }

        if ($jenis === 'pengmas') {
            $params = $this->getPengmasExportData($tahun, $semester, $user_id);
            $headings = ['No', 'Judul PKM', 'Nama Dosen', 'NIP', 'Jenis Hibah', 'Skema', 'Mitra', 'Jumlah Peserta', 'Tahun', 'Semester', 'Sumber Dana', 'Anggaran', 'Tanggal Mulai', 'Tanggal Selesai', 'Tim Abdimas', 'NIP Anggota', 'Anggota Mahasiswa', 'NIM Mahasiswa', 'SDG', 'Status', 'Status Verifikasi'];
            return Excel::download(new TriDharmaExport($params['data'], 'pengmas', $headings, $params['merges']), $filename);
        }

        return back()->with('error', 'Jenis laporan tidak valid.');
    }

    private function getReportQuery($modelClass, $tahun, $semester, $user_id)
    {
        $query = $modelClass::with('user');

        if ($tahun) {
            $query->where('tahun', 'like', $tahun . '%');
        }

        if ($semester) {
            $query->where('semester', $semester);
        }
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        return $query;
    }

    /**
     * Get Penelitian data for export
     */
    private function getPenelitianExportData($tahun, $semester, $user_id)
    {
        $query = $this->getReportQuery(Penelitian::class, $tahun, $semester, $user_id);
        $items = $query->orderBy('created_at', 'desc')->get();
        $data = collect();
        $no = 1;

        foreach ($items as $item) {
            $anggota = $this->parseArrayField($item->getAttributes()['anggota'] ?? $item->anggota);
            $namaDosen = !empty($anggota) ? $anggota[0] : ($item->user ? $item->user->name : '-');

            $data->push([
                $no++,
                $item->judul_penelitian,
                $namaDosen,
                $item->user ? $item->user->nip : '-',
                $item->jenis,
                $item->tahun,
                $item->semester,
                $item->sumber_dana ?? '-',
                $item->anggaran ? number_format((float) $item->anggaran, 0, ',', '.') : '-',
                $item->tanggal_mulai ?? '-',
                $item->tanggal_selesai ?? '-',
                $item->status,
                $item->status_verifikasi,
            ]);
        }

        return ['data' => $data, 'merges' => []];
    }

    /**
     * Get Publikasi data for export
     */
    private function getPublikasiExportData($tahun, $semester, $user_id)
    {
        $query = $this->getReportQuery(Publikasi::class, $tahun, $semester, $user_id);
        $items = $query->orderBy('created_at', 'desc')->get();
        $data = collect();
        $no = 1;

        foreach ($items as $item) {
            $penulis = $this->parseArrayField($item->getAttributes()['penulis'] ?? $item->penulis);
            $namaDosen = !empty($penulis) ? $penulis[0] : ($item->user ? $item->user->name : '-');

            $data->push([
                $no++,
                $item->judul_publikasi,
                $namaDosen,
                $item->user ? $item->user->nip : '-',
                $item->jenis,
                $item->nama_publikasi ?? '-',
                $item->penerbit ?? '-',
                $item->issn_isbn ?? '-',
                $item->volume ?? '-',
                $item->nomor ?? '-',
                $item->halaman ?? '-',
                $item->tanggal_terbit ?? '-',
                $item->indexing ?? '-',
                $item->quartile ?? '-',
                $item->doi ?? '-',
                $item->url ?? '-',
                $item->tahun,
                $item->semester,
                $item->status_verifikasi,
            ]);
        }

        return ['data' => $data, 'merges' => []];
    }

    /**
     * Get Pengmas data for export
     */
    private function getPengmasExportData($tahun, $semester, $user_id)
    {
        $query = $this->getReportQuery(PengabdianMasyarakat::class, $tahun, $semester, $user_id);
        $items = $query->orderBy('created_at', 'desc')->get();
        $data = collect();
        $merges = [];
        $no = 1;

        // Current excel row starts at 2 (1 is header)
        $currentRow = 2;

        foreach ($items as $item) {
            $timAbdimas = $this->parseArrayField($item->getAttributes()['tim_abdimas'] ?? $item->tim_abdimas);
            $dosenNip = $this->parseArrayField($item->getAttributes()['dosen_nip'] ?? $item->dosen_nip);
            $anggotaMahasiswa = $this->parseArrayField($item->getAttributes()['anggota_mahasiswa'] ?? $item->anggota_mahasiswa);
            $mahasiswaNim = $this->parseArrayField($item->getAttributes()['mahasiswa_nim'] ?? $item->mahasiswa_nim);

            $namaDosen = !empty($timAbdimas) ? $timAbdimas[0] : ($item->user ? $item->user->name : '-');

            $countTim = count($timAbdimas);
            $countMhs = count($anggotaMahasiswa);
            $maxRows = max($countTim, $countMhs, 1);

            // Calculate Merges
            if ($maxRows > 1) {
                $endRow = $currentRow + $maxRows - 1;
                // Columns A-N (1-14) shared
                $sharedCols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
                foreach ($sharedCols as $col) {
                    $merges[] = "{$col}{$currentRow}:{$col}{$endRow}";
                }
                // Tail columns: S (19), T (20), U (21)
                $tailCols = ['S', 'T', 'U'];
                foreach ($tailCols as $col) {
                    $merges[] = "{$col}{$currentRow}:{$col}{$endRow}";
                }
            }

            for ($i = 0; $i < $maxRows; $i++) {
                $data->push([
                    $i === 0 ? $no++ : '',
                    $item->judul_pkm,
                    $namaDosen,
                    $item->user ? $item->user->nip : '-',
                    $item->jenis_hibah ?? '-',
                    $item->skema ?? '-',
                    $item->mitra ?? '-',
                    $item->jumlah_peserta ?? '-',
                    $item->tahun,
                    $item->semester,
                    $item->sumber_dana ?? '-',
                    $item->anggaran ? number_format((float) $item->anggaran, 0, ',', '.') : '-',
                    $item->tanggal_mulai ?? '-',
                    $item->tanggal_selesai ?? '-',
                    // Member data
                    $timAbdimas[$i] ?? '-',
                    $dosenNip[$i] ?? '-',
                    $anggotaMahasiswa[$i] ?? '-',
                    $mahasiswaNim[$i] ?? '-',

                    $item->sdg ?? '-',
                    $item->status,
                    $item->status_verifikasi,
                ]);
            }

            $currentRow += $maxRows;
        }

        return ['data' => $data, 'merges' => $merges];
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $tahun = $request->get('tahun');
        $semester = $request->get('semester');
        $jenis = $request->get('jenis', 'all');
        $user_id = $request->get('user_id');

        $stats = $this->getStatistics($tahun, $semester, $user_id);
        $data = [];

        if ($jenis === 'all' || $jenis === 'penelitian') {
            $query = Penelitian::with('user');
            if ($tahun) {
                $query->where('tahun', 'like', $tahun . '%');
            } else {
                $query->rentangTahun(2022);
            }

            if ($semester)
                $query->where('semester', $semester);
            if ($user_id)
                $query->where('user_id', $user_id);
            $data['penelitian'] = $query->get();
        }

        if ($jenis === 'all' || $jenis === 'publikasi') {
            $query = Publikasi::with('user');
            if ($tahun) {
                $query->where('tahun', 'like', $tahun . '%');
            } else {
                $query->rentangTahun(2022);
            }

            if ($user_id)
                $query->where('user_id', $user_id);
            $data['publikasi'] = $query->get();
        }

        if ($jenis === 'all' || $jenis === 'pengmas') {
            $query = PengabdianMasyarakat::with('user');
            if ($tahun) {
                $query->where('tahun', 'like', $tahun . '%');
            } else {
                $query->rentangTahun(2022);
            }

            if ($semester)
                $query->where('semester', $semester);
            if ($user_id)
                $query->where('user_id', $user_id);
            $data['pengmas'] = $query->get();
        }

        return view('reports.pdf', compact('stats', 'data', 'tahun', 'semester', 'jenis'));
    }

    /**
     * Productivity report
     */
    public function productivity(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $dosens = User::where('role', 'dosen')
            ->where('is_active', true)
            ->get();

        $productivity = [];

        foreach ($dosens as $dosen) {
            $productivity[] = [
                'dosen' => $dosen,
                'penelitian' => Penelitian::where('user_id', $dosen->id)
                    ->where('tahun', 'like', $tahun . '%')
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'publikasi' => Publikasi::where('user_id', $dosen->id)
                    ->where('tahun', 'like', $tahun . '%')
                    ->where('status_verifikasi', 'verified')
                    ->count(),
                'pengmas' => PengabdianMasyarakat::where('user_id', $dosen->id)
                    ->where('tahun', 'like', $tahun . '%')
                    ->where('status_verifikasi', 'verified')
                    ->count(),
            ];
        }

        usort($productivity, function ($a, $b) {
            $totalA = $a['penelitian'] + $a['publikasi'] + $a['pengmas'];
            $totalB = $b['penelitian'] + $b['publikasi'] + $b['pengmas'];
            return $totalB - $totalA;
        });

        $years = range(date('Y'), date('Y') - 5);

        return view('reports.productivity', compact('productivity', 'years', 'tahun'));
    }

    /**
     * Parse array field from database value
     */
    private function parseArrayField($value)
    {
        try {
            if (is_array($value)) {
                return $value;
            }

            if (is_null($value) || $value === '') {
                return [];
            }

            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            if (is_string($value)) {
                return array_map('trim', explode(',', $value));
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
