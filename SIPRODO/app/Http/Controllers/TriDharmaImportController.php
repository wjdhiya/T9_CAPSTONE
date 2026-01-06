<?php

namespace App\Http\Controllers;

use App\Imports\RawArrayImport;
use App\Models\PengabdianMasyarakat;
use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TriDharmaImportController extends Controller
{
    public function index()
    {
        return view('imports.index');
    }

    public function importAuto(Request $request)
    {
        /** @var User|null $actor */
        $actor = Auth::user();
        if (!($actor && $actor->isKaprodi())) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan import.');
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:20480',
        ]);

        $sheets = Excel::toArray(new RawArrayImport(), $validated['file']);
        $rowsRaw = $sheets[0] ?? [];
        if (count($rowsRaw) < 2) {
            return back()->with('error', 'File kosong atau tidak memiliki data.');
        }

        $headers = array_map(fn($h) => $this->normalizeHeader($h), array_shift($rowsRaw));

        $batches = [
            'penelitian' => [],
            'publikasi' => [],
            'pengmas' => [],
        ];
        $errors = [];

        foreach ($rowsRaw as $i => $row) {
            $rowNumber = $i + 2;
            $assoc = $this->rowToAssoc($headers, $row);

            $nidn = trim((string)($assoc['nidn'] ?? ''));
            if ($nidn === '') {
                $errors[] = ['row' => $rowNumber, 'message' => 'NIDN wajib diisi'];
                continue;
            }

            $user = User::where('nidn', $nidn)->where('role', User::ROLE_DOSEN)->first();
            if (!$user) {
                $errors[] = ['row' => $rowNumber, 'message' => "NIDN tidak ditemukan: {$nidn}"];
                continue;
            }

            $detectedType = $this->detectType($assoc);
            if ($detectedType === null) {
                $errors[] = ['row' => $rowNumber, 'message' => 'Kategori tidak terdeteksi (gunakan kolom kategori/jenis_data atau lengkapi field kunci)'];
                continue;
            }

            try {
                $payload = $this->buildPayload($detectedType, $assoc, $user->id);
                $batches[$detectedType][] = $payload;
            } catch (\InvalidArgumentException $e) {
                $errors[] = ['row' => $rowNumber, 'message' => $e->getMessage()];
            }
        }

        $result = [
            'penelitian' => ['processed' => 0, 'skipped_verified' => 0],
            'publikasi' => ['processed' => 0, 'skipped_verified' => 0],
            'pengmas' => ['processed' => 0, 'skipped_verified' => 0],
        ];

        foreach (['penelitian', 'publikasi', 'pengmas'] as $type) {
            if (count($batches[$type]) === 0) {
                continue;
            }

            $uniqueBy = $this->uniqueBy($type);
            $updateColumns = $this->updateColumns($type);

            $existingMap = $this->loadExistingMap($type, $batches[$type]);

            $upsertRows = [];
            $skippedVerified = 0;

            foreach ($batches[$type] as $r) {
                $key = $this->composeKey($r, $uniqueBy);
                $existing = $existingMap[$key] ?? null;
                if ($existing && ($existing->status_verifikasi ?? null) === 'verified') {
                    $skippedVerified++;
                    continue;
                }
                $upsertRows[] = $r;
            }

            if (count($upsertRows) > 0) {
                $model = $this->modelClass($type);
                $model::upsert($upsertRows, $uniqueBy, $updateColumns);
            }

            $result[$type]['processed'] = count($upsertRows);
            $result[$type]['skipped_verified'] = $skippedVerified;
        }

        $failedCount = count($errors);
        $successCount = ($result['penelitian']['processed'] ?? 0) + ($result['publikasi']['processed'] ?? 0) + ($result['pengmas']['processed'] ?? 0);
        $skippedVerifiedTotal = ($result['penelitian']['skipped_verified'] ?? 0) + ($result['publikasi']['skipped_verified'] ?? 0) + ($result['pengmas']['skipped_verified'] ?? 0);

        if ($successCount === 0) {
            return back()->with('error', 'Tidak ada data valid untuk diimport.')->with('import_errors', $errors);
        }

        return back()
            ->with('success', "Import auto-detect selesai. Berhasil diproses: {$successCount}. Dilewati (verified): {$skippedVerifiedTotal}. Error: {$failedCount}.")
            ->with('import_errors', $errors);
    }

    public function import(Request $request, string $type)
    {
        /** @var User|null $actor */
        $actor = Auth::user();
        if (!($actor && $actor->isKaprodi())) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan import.');
        }

        $type = strtolower($type);
        if (!in_array($type, ['penelitian', 'publikasi', 'pengmas'], true)) {
            abort(404);
        }

        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:20480',
        ]);

        $sheets = Excel::toArray(new RawArrayImport(), $validated['file']);
        $rowsRaw = $sheets[0] ?? [];
        if (count($rowsRaw) < 2) {
            return back()->with('error', 'File kosong atau tidak memiliki data.');
        }

        $headers = array_map(fn($h) => $this->normalizeHeader($h), array_shift($rowsRaw));

        $rows = [];
        $errors = [];

        foreach ($rowsRaw as $i => $row) {
            $rowNumber = $i + 2;
            $assoc = $this->rowToAssoc($headers, $row);

            $nidn = trim((string)($assoc['nidn'] ?? ''));
            if ($nidn === '') {
                $errors[] = ['row' => $rowNumber, 'message' => 'NIDN wajib diisi'];
                continue;
            }

            $user = User::where('nidn', $nidn)->where('role', User::ROLE_DOSEN)->first();
            if (!$user) {
                $errors[] = ['row' => $rowNumber, 'message' => "NIDN tidak ditemukan: {$nidn}"]; 
                continue;
            }

            try {
                $payload = $this->buildPayload($type, $assoc, $user->id);
                $rows[] = $payload;
            } catch (\InvalidArgumentException $e) {
                $errors[] = ['row' => $rowNumber, 'message' => $e->getMessage()];
            }
        }

        if (count($rows) === 0) {
            return back()->with('error', 'Tidak ada data valid untuk diimport.')->with('import_errors', $errors);
        }

        $uniqueBy = $this->uniqueBy($type);
        $updateColumns = $this->updateColumns($type);

        $existingMap = $this->loadExistingMap($type, $rows);

        $upsertRows = [];
        $skippedVerified = 0;

        foreach ($rows as $r) {
            $key = $this->composeKey($r, $uniqueBy);
            $existing = $existingMap[$key] ?? null;

            if ($existing && ($existing->status_verifikasi ?? null) === 'verified') {
                $skippedVerified++;
                continue;
            }

            $upsertRows[] = $r;
        }

        if (count($upsertRows) === 0) {
            return back()->with('error', 'Semua baris dilewati karena data sudah terverifikasi.')->with('import_errors', $errors);
        }

        $model = $this->modelClass($type);
        $model::upsert($upsertRows, $uniqueBy, $updateColumns);

        $successCount = count($upsertRows);
        $failedCount = count($errors);

        return back()
            ->with('success', "Import {$type} selesai. Berhasil diproses: {$successCount}. Dilewati (verified): {$skippedVerified}. Error: {$failedCount}.")
            ->with('import_errors', $errors);
    }

    private function normalizeHeader($value): string
    {
        $v = strtolower(trim((string) $value));
        $v = preg_replace('/\s+/', '_', $v);
        $v = preg_replace('/[^a-z0-9_]/', '', $v);
        return $v;
    }

    private function detectType(array $assoc): ?string
    {
        $category = strtolower(trim((string)($assoc['kategori'] ?? $assoc['jenis_data'] ?? $assoc['category'] ?? '')));
        if ($category !== '') {
            if (in_array($category, ['penelitian', 'riset', 'research'], true)) {
                return 'penelitian';
            }
            if (in_array($category, ['publikasi', 'publication'], true)) {
                return 'publikasi';
            }
            if (in_array($category, ['pengmas', 'pengabdian', 'pengabdian_masyarakat', 'pkm', 'community_service'], true)) {
                return 'pengmas';
            }
        }

        $hasPublikasiKey = !empty($assoc['nama_publikasi'] ?? $assoc['nama_jurnal'] ?? null)
            || !empty($assoc['issn_isbn'] ?? null)
            || !empty($assoc['doi'] ?? null);

        $hasPengmasKey = !empty($assoc['lokasi'] ?? null)
            || !empty($assoc['mitra'] ?? null)
            || !empty($assoc['jumlah_peserta'] ?? null);

        if ($hasPublikasiKey) {
            return 'publikasi';
        }

        if ($hasPengmasKey) {
            return 'pengmas';
        }

        if (!empty($assoc['judul'] ?? null)) {
            return 'penelitian';
        }

        return null;
    }

    private function rowToAssoc(array $headers, array $row): array
    {
        $assoc = [];
        foreach ($headers as $idx => $key) {
            if ($key === '') {
                continue;
            }
            $assoc[$key] = $row[$idx] ?? null;
        }
        return $assoc;
    }

    private function buildPayload(string $type, array $assoc, int $userId): array
    {
        $judul = trim((string)($assoc['judul'] ?? ''));
        $tahun = trim((string)($assoc['tahun'] ?? ''));
        $semester = strtolower(trim((string)($assoc['semester'] ?? '')));

        if ($judul === '') {
            throw new \InvalidArgumentException('Judul wajib diisi');
        }
        if ($tahun === '' || !is_numeric($tahun)) {
            throw new \InvalidArgumentException('Tahun akademik wajib diisi (angka)');
        }
        if (!in_array($semester, ['ganjil', 'genap'], true)) {
            throw new \InvalidArgumentException('Semester harus ganjil atau genap');
        }

        $base = [
            'user_id' => $userId,
            'judul' => $judul,
            'tahun' => (int) $tahun,
            'semester' => $semester,
            'status_verifikasi' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
            'catatan_verifikasi' => null,
        ];

        if ($type === 'penelitian') {
            $jenis = strtolower(trim((string)($assoc['jenis'] ?? '')));
            if (!in_array($jenis, ['mandiri', 'hibah_internal', 'hibah_eksternal', 'kerjasama'], true)) {
                throw new \InvalidArgumentException('Jenis penelitian tidak valid');
            }

            $status = strtolower(trim((string)($assoc['status'] ?? 'proposal')));
            if (!in_array($status, ['proposal', 'berjalan', 'selesai', 'ditolak'], true)) {
                $status = 'proposal';
            }

            return array_merge($base, [
                'abstrak' => $assoc['abstrak'] ?? null,
                'jenis' => $jenis,
                'sumber_dana' => $assoc['sumber_dana'] ?? null,
                'dana' => $this->toDecimal($assoc['dana'] ?? null),
                'tanggal_mulai' => $this->toDate($assoc['tanggal_mulai'] ?? null),
                'tanggal_selesai' => $this->toDate($assoc['tanggal_selesai'] ?? null),
                'status' => $status,
                'anggota' => $this->toJsonArray($assoc['anggota'] ?? null),
                'mahasiswa_terlibat' => $this->toJsonArray($assoc['mahasiswa_terlibat'] ?? null),
                'catatan' => $assoc['catatan'] ?? null,
            ]);
        }

        if ($type === 'publikasi') {
            $jenis = strtolower(trim((string)($assoc['jenis'] ?? '')));
            if (!in_array($jenis, ['jurnal', 'prosiding', 'buku', 'book_chapter', 'paten', 'hki'], true)) {
                throw new \InvalidArgumentException('Jenis publikasi tidak valid');
            }

            $namaPublikasi = trim((string)($assoc['nama_publikasi'] ?? $assoc['nama_jurnal'] ?? ''));
            if ($namaPublikasi === '') {
                throw new \InvalidArgumentException('Nama publikasi (nama_publikasi) wajib diisi');
            }

            $indexing = strtolower(trim((string)($assoc['indexing'] ?? '')));
            if ($indexing === '') {
                $indexing = null;
            }

            $quartile = strtoupper(trim((string)($assoc['quartile'] ?? '')));
            if ($quartile === '') {
                $quartile = null;
            }

            return array_merge($base, [
                'abstrak' => $assoc['abstrak'] ?? null,
                'jenis' => $jenis,
                'nama_publikasi' => $namaPublikasi,
                'penerbit' => $assoc['penerbit'] ?? null,
                'issn_isbn' => $assoc['issn_isbn'] ?? null,
                'volume' => $assoc['volume'] ?? null,
                'nomor' => $assoc['nomor'] ?? null,
                'halaman' => $assoc['halaman'] ?? null,
                'tanggal_terbit' => $this->toDate($assoc['tanggal_terbit'] ?? null),
                'quartile' => $quartile,
                'indexing' => $indexing,
                'doi' => $assoc['doi'] ?? null,
                'url' => $assoc['url'] ?? null,
                'penulis' => $this->toJsonArray($assoc['penulis'] ?? null),
                'mahasiswa_terlibat' => $this->toJsonArray($assoc['mahasiswa_terlibat'] ?? null),
                'catatan' => $assoc['catatan'] ?? null,
            ]);
        }

        if ($type === 'pengmas') {
            $jenis = strtolower(trim((string)($assoc['jenis'] ?? '')));
            if (!in_array($jenis, ['internal', 'eksternal', 'mandiri'], true)) {
                throw new \InvalidArgumentException('Jenis pengabdian tidak valid');
            }

            $status = strtolower(trim((string)($assoc['status'] ?? 'proposal')));
            if (!in_array($status, ['proposal', 'berjalan', 'selesai', 'ditolak'], true)) {
                $status = 'proposal';
            }

            return array_merge($base, [
                'deskripsi' => $assoc['deskripsi'] ?? $assoc['abstrak'] ?? null,
                'jenis' => $jenis,
                'sumber_dana' => $assoc['sumber_dana'] ?? null,
                'dana' => $this->toDecimal($assoc['dana'] ?? null),
                'tanggal_mulai' => $this->toDate($assoc['tanggal_mulai'] ?? null),
                'tanggal_selesai' => $this->toDate($assoc['tanggal_selesai'] ?? null),
                'lokasi' => $assoc['lokasi'] ?? null,
                'mitra' => $assoc['mitra'] ?? null,
                'jumlah_peserta' => $this->toInt($assoc['jumlah_peserta'] ?? null),
                'status' => $status,
                'anggota' => $this->toJsonArray($assoc['anggota'] ?? null),
                'mahasiswa_terlibat' => $this->toJsonArray($assoc['mahasiswa_terlibat'] ?? null),
                'catatan' => $assoc['catatan'] ?? null,
            ]);
        }

        throw new \InvalidArgumentException('Tipe import tidak dikenal');
    }

    private function toJsonArray($value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            $arr = array_values(array_filter(array_map('trim', array_map('strval', $value)), fn($v) => $v !== ''));
            return count($arr) ? json_encode($arr) : null;
        }

        $s = trim((string) $value);
        if ($s === '') {
            return null;
        }

        if (str_starts_with($s, '[') && str_ends_with($s, ']')) {
            return $s;
        }

        $arr = array_values(array_filter(array_map('trim', explode(',', $s)), fn($v) => $v !== ''));
        return count($arr) ? json_encode($arr) : null;
    }

    private function toDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $s = str_replace(['.', ','], ['', '.'], (string) $value);
        if (!is_numeric($s)) {
            return null;
        }
        return (float) $s;
    }

    private function toInt($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return (int) $value;
        }
        return null;
    }

    private function toDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }
        $s = trim((string) $value);
        if ($s === '') {
            return null;
        }

        try {
            return Carbon::parse($s)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function modelClass(string $type): string
    {
        return match ($type) {
            'penelitian' => Penelitian::class,
            'publikasi' => Publikasi::class,
            'pengmas' => PengabdianMasyarakat::class,
            default => Penelitian::class,
        };
    }

    private function uniqueBy(string $type): array
    {
        return match ($type) {
            'penelitian' => ['user_id', 'judul', 'tahun', 'semester'],
            'publikasi' => ['user_id', 'judul', 'jenis', 'tahun', 'semester'],
            'pengmas' => ['user_id', 'judul', 'tahun', 'semester'],
            default => ['user_id', 'judul', 'tahun', 'semester'],
        };
    }

    private function updateColumns(string $type): array
    {
        $skip = ['status_verifikasi', 'verified_by', 'verified_at', 'catatan_verifikasi', 'created_at', 'updated_at', 'deleted_at'];

        $columns = match ($type) {
            'penelitian' => (new Penelitian())->getFillable(),
            'publikasi' => (new Publikasi())->getFillable(),
            'pengmas' => (new PengabdianMasyarakat())->getFillable(),
            default => (new Penelitian())->getFillable(),
        };

        return array_values(array_filter($columns, fn($c) => !in_array($c, $skip, true)));
    }

    private function loadExistingMap(string $type, array $rows): array
    {
        $uniqueBy = $this->uniqueBy($type);
        $model = $this->modelClass($type);

        $query = $model::query();

        $query->where(function ($q) use ($rows, $uniqueBy) {
            foreach ($rows as $r) {
                $q->orWhere(function ($qq) use ($r, $uniqueBy) {
                    foreach ($uniqueBy as $col) {
                        $qq->where($col, $r[$col]);
                    }
                });
            }
        });

        $existing = $query->get();
        $map = [];

        foreach ($existing as $e) {
            $payload = [];
            foreach ($uniqueBy as $col) {
                $payload[$col] = $e->{$col};
            }
            $map[$this->composeKey($payload, $uniqueBy)] = $e;
        }

        return $map;
    }

    private function composeKey(array $payload, array $uniqueBy): string
    {
        $parts = [];
        foreach ($uniqueBy as $col) {
            $parts[] = (string)($payload[$col] ?? '');
        }
        return implode('|', $parts);
    }
}
