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

    private function isIdentifierHeader(string $h): bool
    {
        if ($h === '') {
            return false;
        }

        if (in_array($h, ['user_id', 'id_user', 'dosen_id', 'id_dosen'], true)) {
            return true;
        }

        if (str_contains($h, 'nidn')) {
            return true;
        }

        if (str_contains($h, 'nip') && !str_contains($h, 'mahasiswa')) {
            return true;
        }

        if (str_contains($h, 'email')) {
            return true;
        }

        if (str_contains($h, 'nama_dosen') || $h === 'nama' || $h === 'name' || $h === 'dosen') {
            return true;
        }

        // For Pengmas imports, identity might be embedded inside team/member columns
        if (str_contains($h, 'tim_abdimas') || (str_contains($h, 'tim') && str_contains($h, 'abdimas'))) {
            return true;
        }
        if (str_contains($h, 'anggota_abdimas') || (str_contains($h, 'anggota') && str_contains($h, 'abdimas'))) {
            return true;
        }

        return false;
    }

    private function isRowEmpty(array $row): bool
    {
        foreach ($row as $cell) {
            if ($cell === null) {
                continue;
            }
            if (trim((string) $cell) !== '') {
                return false;
            }
        }
        return true;
    }

    private function normalizeNip($value): string
    {
        if ($value === null) {
            return '';
        }

        // Excel can provide numeric/scientific notation; normalize to digit string.
        // IMPORTANT: if the value is a numeric-looking string, do NOT cast to float
        // because it can drop leading zeros.
        if (is_int($value) || is_float($value)) {
            $float = (float) $value;
            // Avoid scientific notation in string casting.
            $s = number_format($float, 0, '', '');
            return trim($s);
        }

        $s = trim((string) $value);
        if ($s === '') {
            return '';
        }

        // Handle scientific notation represented as string.
        if (preg_match('/^[0-9]+(\.[0-9]+)?[eE][\+\-]?[0-9]+$/', $s)) {
            $s = number_format((float) $s, 0, '', '');
            return trim($s);
        }

        // Strip common separators but keep digits.
        $clean = str_replace([' ', "\t", "\n", "\r", '-', '.', '/', '\\', ',', ';', ':', '(', ')'], '', $s);

        // If it becomes pure digits, keep it (preserves leading zeros)
        if (preg_match('/^[0-9]+$/', $clean)) {
            return $clean;
        }

        // Otherwise, extract the FIRST reasonably-long digit sequence
        if (preg_match('/([0-9]{6,})/', $s, $m)) {
            return $m[1];
        }

        return '';
    }

    // alias
    private function normalizeNidn($value): string
    {
        return $this->normalizeNip($value);
    }

    private function extractNipsFromText($value): array
    {
        $s = trim((string) ($value ?? ''));
        if ($s === '') {
            return [];
        }

        $matches = [];
        preg_match_all('/\bNIP\s*[:=]?\s*([0-9]{6,})\b/i', $s, $matches);
        $nips = $matches[1] ?? [];

        // Fallback: any long digit sequence (if no explicit NIP label)
        if (count($nips) === 0) {
            preg_match_all('/\b([0-9]{8,})\b/', $s, $matches);
            $nips = $matches[1] ?? [];
        }

        $nips = array_values(array_unique(array_filter(array_map(fn($x) => $this->normalizeNip($x), $nips), fn($x) => $x !== '')));
        return $nips;
    }

    private function extractNimsFromText($value): array
    {
        $s = trim((string) ($value ?? ''));
        if ($s === '') {
            return [];
        }

        $matches = [];
        preg_match_all('/\bNIM\s*[:=]?\s*([0-9]{6,})\b/i', $s, $matches);
        $nims = $matches[1] ?? [];

        // Fallback: any long digit sequence (common NIM length 8+)
        if (count($nims) === 0) {
            preg_match_all('/\b([0-9]{8,})\b/', $s, $matches);
            $nims = $matches[1] ?? [];
        }

        $nims = array_values(array_unique(array_filter(array_map(fn($x) => $this->normalizeNip($x), $nims), fn($x) => $x !== '')));
        return $nims;
    }

    private function detectCsvDelimiter($uploadedFile): string
    {
        try {
            $ext = strtolower((string) ($uploadedFile?->getClientOriginalExtension() ?? ''));
            if (!in_array($ext, ['csv', 'txt'], true)) {
                return ',';
            }

            $path = $uploadedFile->getRealPath();
            if (!$path) {
                return ',';
            }

            $fh = @fopen($path, 'rb');
            if (!$fh) {
                return ',';
            }

            $line = fgets($fh);
            fclose($fh);
            $sample = $line !== false ? $line : '';

            $counts = [
                ',' => substr_count($sample, ','),
                ';' => substr_count($sample, ';'),
                "\t" => substr_count($sample, "\t"),
            ];

            arsort($counts);
            $best = array_key_first($counts);
            if (($counts[$best] ?? 0) <= 0) {
                return ',';
            }
            return $best;
        } catch (\Throwable $e) {
            return ',';
        }
    }

    private function findUserByNumericFromRow(array $row, string $column, array &$cache): ?User
    {
        foreach ($row as $cell) {
            $candidate = $this->normalizeNip($cell);
            if ($candidate === '') {
                continue;
            }
            if (!preg_match('/^[0-9]{6,}$/', $candidate)) {
                continue;
            }

            $cacheKey = $column . ':' . $candidate;
            if (array_key_exists($cacheKey, $cache)) {
                return $cache[$cacheKey];
            }

            $user = User::where($column, $candidate)->where('role', User::ROLE_DOSEN)->first();
            if (!$user && $column === 'nip') {
                $alt = ltrim($candidate, '0');
                if ($alt !== '' && $alt !== $candidate) {
                    $user = User::where($column, $alt)->where('role', User::ROLE_DOSEN)->first();
                }
            }
            $cache[$cacheKey] = $user;
            if ($user) {
                return $user;
            }
        }
        return null;
    }

    private function resolveUserForImport(array $assoc, array $row, array &$cache, array &$debug = []): ?User
    {
        $debug = [];

        // 1) user_id
        $userIdRaw = $this->getVal($assoc, ['user_id', 'id_user', 'dosen_id', 'id_dosen'], '');
        if ($userIdRaw !== '' && is_numeric($userIdRaw)) {
            $debug[] = 'user_id=' . (string) $userIdRaw;
            $cacheKey = 'id:' . (string) $userIdRaw;
            if (array_key_exists($cacheKey, $cache)) {
                return $cache[$cacheKey];
            }
            $user = User::where('id', (int) $userIdRaw)->where('role', User::ROLE_DOSEN)->first();
            $cache[$cacheKey] = $user;
            if ($user) {
                return $user;
            }
        }

        // 2) nidn (rename map to nip)
        $nidn = $this->normalizeNip($this->getVal($assoc, ['nidn', 'nidn_dosen', 'nidn_pengusul', 'nidn_ketua', 'nomor_induk_dosen_nasional'], ''));
        if ($nidn === '') {
            foreach (array_keys($assoc) as $k) {
                if (strpos($k, 'nidn') !== false) {
                    $nidn = $this->normalizeNip($assoc[$k] ?? null);
                    if ($nidn !== '') {
                        break;
                    }
                }
            }
        }
        if ($nidn !== '') {
            $debug[] = 'nidn_mapped_to_nip=' . $nidn;
            $cacheKey = 'nip:' . $nidn;
            if (array_key_exists($cacheKey, $cache)) {
                return $cache[$cacheKey];
            }
            $user = User::where('nip', $nidn)->where('role', User::ROLE_DOSEN)->first();
            $cache[$cacheKey] = $user;
            if ($user) {
                return $user;
            }
        }

        // 3) nip
        $nip = $this->normalizeNip($this->getVal($assoc, ['nip', 'dosen_nip', 'nip_dosen', 'nip_ketua'], ''));
        if ($nip === '') {
            foreach (array_keys($assoc) as $k) {
                if (strpos($k, 'nip') !== false && strpos($k, 'mahasiswa') === false) {
                    $nip = $this->normalizeNip($assoc[$k] ?? null);
                    if ($nip !== '') {
                        break;
                    }
                }
            }
        }
        if ($nip !== '') {
            $debug[] = 'nip=' . $nip;
            $cacheKey = 'nip:' . $nip;
            if (array_key_exists($cacheKey, $cache)) {
                return $cache[$cacheKey];
            }
            $user = User::where('nip', $nip)->where('role', User::ROLE_DOSEN)->first();
            if (!$user) {
                $alt = ltrim($nip, '0');
                if ($alt !== '' && $alt !== $nip) {
                    $user = User::where('nip', $alt)->where('role', User::ROLE_DOSEN)->first();
                }
            }

            if (!$user) {
                // Fallback
                // Removing nidn check as it is now nip
            }
            $cache[$cacheKey] = $user;
            if ($user) {
                return $user;
            }
        }

        // 4) email
        $email = strtolower(trim((string) $this->getVal($assoc, ['email', 'email_dosen', 'email_ketua'], '')));
        if ($email === '') {
            foreach (array_keys($assoc) as $k) {
                if (strpos($k, 'email') !== false) {
                    $email = strtolower(trim((string) ($assoc[$k] ?? '')));
                    if ($email !== '') {
                        break;
                    }
                }
            }
        }
        if ($email !== '') {
            $debug[] = 'email=' . $email;
            $cacheKey = 'email:' . $email;
            if (array_key_exists($cacheKey, $cache)) {
                return $cache[$cacheKey];
            }
            $user = User::where('email', $email)->where('role', User::ROLE_DOSEN)->first();
            $cache[$cacheKey] = $user;
            if ($user) {
                return $user;
            }
        }

        // 5) name (only if unique match)
        $name = trim((string) $this->getVal($assoc, ['nama_dosen', 'nama', 'dosen', 'name'], ''));
        if ($name !== '') {
            $debug[] = 'name=' . $name;
            $cacheKey = 'name:' . strtolower($name);
            if (array_key_exists($cacheKey, $cache)) {
                return $cache[$cacheKey];
            }
            $matches = User::where('role', User::ROLE_DOSEN)->whereRaw('LOWER(name) = ?', [strtolower($name)])->limit(2)->get();
            $user = ($matches->count() === 1) ? $matches->first() : null;
            $cache[$cacheKey] = $user;
            if ($user) {
                return $user;
            }
        }

        // 6) Pengmas: infer dosen from NIP inside tim abdimas field (often no explicit nip/user_id column)
        $timAbdimas = null;
        foreach ($assoc as $k => $v) {
            $kk = (string) $k;
            if ((str_contains($kk, 'tim') && str_contains($kk, 'abdimas')) || str_contains($kk, 'tim_abdimas')) {
                $timAbdimas = $v;
                break;
            }
        }
        if ($timAbdimas !== null && trim((string) $timAbdimas) !== '') {
            $nips = $this->extractNipsFromText($timAbdimas);
            if (count($nips) > 0) {
                $debug[] = 'tim_abdimas_nip=' . implode(',', array_slice($nips, 0, 3));
                foreach ($nips as $nipFromTeam) {
                    $cacheKey = 'nip:' . $nipFromTeam;
                    if (array_key_exists($cacheKey, $cache)) {
                        $u = $cache[$cacheKey];
                        if ($u) {
                            return $u;
                        }
                        continue;
                    }
                    $u = User::where('nip', $nipFromTeam)->where('role', User::ROLE_DOSEN)->first();
                    if (!$u) {
                        $alt = ltrim($nipFromTeam, '0');
                        if ($alt !== '' && $alt !== $nipFromTeam) {
                            $u = User::where('nip', $alt)->where('role', User::ROLE_DOSEN)->first();
                        }
                    }

                    if (!$u) {
                        // Fallback removed
                    }
                    $cache[$cacheKey] = $u;
                    if ($u) {
                        return $u;
                    }
                }
            }
        }

        // Fallback: scan row cells for NIDN / NIP that exists
        $user = $this->findUserByNumericFromRow($row, 'nip', $cache);
        if ($user) {
            $debug[] = 'row_scan=nip_via_nidn_alias';
            return $user;
        }
        $user = $this->findUserByNumericFromRow($row, 'nip', $cache);
        if ($user) {
            $debug[] = 'row_scan=nip';
        }
        return $user;
    }

    private function findUserByNidnFromRow(array $row, array &$nidnUserCache): ?User
    {
        // Backward compatible wrapper
        // Backward compatible wrapper (scans for nidn column in row but uses normalizeNip)
        return $this->findUserByNumericFromRow($row, 'nip', $nidnUserCache);
    }

    private function findHeaderRowIndex(array $rowsRaw): int
    {
        $maxScan = min(20, count($rowsRaw));
        $identifierAliases = [
            'user_id',
            'id_user',
            'dosen_id',
            'id_dosen',
            'nidn',
            'nidn_dosen',
            'nidn_pengusul',
            'nidn_ketua',
            'nomor_induk_dosen_nasional',
            'nip',
            'dosen_nip',
            'nip_dosen',
            'nip_ketua',
            'email',
            'email_dosen',
            'email_ketua',
            'nama_dosen',
            'nama',
            'name',
        ];
        $judulAliases = ['judul', 'judul_penelitian', 'judul_publikasi', 'judul_pkm', 'judul_pengabdian', 'nama_kegiatan'];

        for ($i = 0; $i < $maxScan; $i++) {
            $row = $rowsRaw[$i] ?? [];
            if (!is_array($row) || $this->isRowEmpty($row)) {
                continue;
            }

            $headers = array_map(fn($h) => $this->normalizeHeader($h), $row);

            // Must have an identifier column header (user_id / nidn / nip / email)
            $idIndexes = [];
            foreach ($headers as $idx => $h) {
                if ($h !== '' && (in_array($h, $identifierAliases, true) || $this->isIdentifierHeader($h))) {
                    $idIndexes[] = $idx;
                }
            }
            if (count($idIndexes) === 0) {
                continue;
            }

            // Header row should have several columns (avoid picking a title row that only contains 'NIDN')
            $nonEmptyHeaderCount = count(array_filter($headers, fn($h) => $h !== ''));
            if ($nonEmptyHeaderCount < 3) {
                continue;
            }

            // Prefer rows that also contain a 'judul' key, but still validate against data below.
            $hasJudul = false;
            foreach ($judulAliases as $k) {
                if (in_array($k, $headers, true)) {
                    $hasJudul = true;
                    break;
                }
            }

            // Validate: under one of the identifier columns, at least one of the next rows contains a value.
            $maxLookahead = min($i + 6, count($rowsRaw));
            $looksValid = false;
            for ($j = $i + 1; $j < $maxLookahead; $j++) {
                $dataRow = $rowsRaw[$j] ?? [];
                if (!is_array($dataRow) || $this->isRowEmpty($dataRow)) {
                    continue;
                }
                foreach ($idIndexes as $col) {
                    $val = $dataRow[$col] ?? null;
                    if ($val === null) {
                        continue;
                    }
                    if (trim((string) $val) !== '') {
                        $looksValid = true;
                        break 2;
                    }
                }
            }

            if ($looksValid) {
                return $i;
            }

            // If it has judul but still failed validation, keep scanning.
            if ($hasJudul) {
                continue;
            }
        }

        return 0;
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

        $delimiter = $this->detectCsvDelimiter($request->file('file'));
        $sheets = Excel::toArray(new RawArrayImport($delimiter), $validated['file']);
        $rowsRaw = $sheets[0] ?? [];
        if (count($rowsRaw) < 2) {
            return back()->with('error', 'File kosong atau tidak memiliki data.');
        }

        $headerIdx = $this->findHeaderRowIndex($rowsRaw);
        $headersRow = $rowsRaw[$headerIdx] ?? [];
        $headers = array_map(fn($h) => $this->normalizeHeader($h), $headersRow);
        $rowsRaw = array_slice($rowsRaw, $headerIdx + 1);

        $identifierAliases = [
            'user_id',
            'id_user',
            'dosen_id',
            'id_dosen',
            'nip',
            'dosen_nip',
            'nip_dosen',
            'nip_ketua',
            'email',
            'email_dosen',
            'email_ketua',
            'nidn',
            'nidn_dosen',
            'nidn_pengusul',
            'nidn_ketua',
            'nomor_induk_dosen_nasional',
            'nama_dosen',
            'nama',
            'name',
        ];
        $hasIdentifierHeader = false;
        foreach ($identifierAliases as $k) {
            if (in_array($k, $headers, true)) {
                $hasIdentifierHeader = true;
                break;
            }
        }
        if (!$hasIdentifierHeader) {
            foreach ($headers as $h) {
                if ($this->isIdentifierHeader($h)) {
                    $hasIdentifierHeader = true;
                    break;
                }
            }
        }
        if (!$hasIdentifierHeader) {
            return back()->with('error', 'Kolom identitas dosen tidak ditemukan pada header file. Gunakan salah satu kolom: user_id / nip / email / nidn.');
        }

        $batches = [
            'penelitian' => [],
            'publikasi' => [],
            'pengmas' => [],
        ];
        $errors = [];

        $nidnUserCache = [];

        foreach ($rowsRaw as $i => $row) {
            if (!is_array($row) || $this->isRowEmpty($row)) {
                continue;
            }
            // original (1-indexed) row number in sheet
            $rowNumber = $headerIdx + 2 + $i;
            $assoc = $this->rowToAssoc($headers, $row);

            $debug = [];
            $user = $this->resolveUserForImport($assoc, $row, $nidnUserCache, $debug);

            // FALLBACK: Jika user tidak ditemukan, gunakan user yang sedang login (Kaprodi)
            if (!$user) {
                $user = Auth::user();
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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses untuk melakukan import.'], 403);
            }
            abort(403, 'Anda tidak memiliki akses untuk melakukan import.');
        }

        $type = strtolower($type);
        if (!in_array($type, ['penelitian', 'publikasi', 'pengmas'], true)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Tipe import tidak valid.'], 404);
            }
            abort(404);
        }

        try {
            $validated = $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:20480',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'File tidak valid. Pastikan format CSV/XLSX dan ukuran maksimal 20MB.', 'errors' => $e->errors()], 422);
            }
            throw $e;
        }

        try {
            $delimiter = $this->detectCsvDelimiter($request->file('file'));
            $sheets = Excel::toArray(new RawArrayImport($delimiter), $validated['file']);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal membaca file: ' . $e->getMessage()], 422);
            }
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        $rowsRaw = $sheets[0] ?? [];
        if (count($rowsRaw) < 2) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'File kosong atau tidak memiliki data.'], 422);
            }
            return back()->with('error', 'File kosong atau tidak memiliki data.');
        }

        $headerIdx = $this->findHeaderRowIndex($rowsRaw);
        $headersRow = $rowsRaw[$headerIdx] ?? [];
        $headers = array_map(fn($h) => $this->normalizeHeader($h), $headersRow);
        $rowsRaw = array_slice($rowsRaw, $headerIdx + 1);

        $identifierAliases = [
            'user_id',
            'id_user',
            'dosen_id',
            'id_dosen',
            'nip',
            'dosen_nip',
            'nip_dosen',
            'nip_ketua',
            'email',
            'email_dosen',
            'email_ketua',
            'nidn',
            'nidn_dosen',
            'nidn_pengusul',
            'nidn_ketua',
            'nomor_induk_dosen_nasional',
            'nama_dosen',
            'nama',
            'name',
        ];
        $hasIdentifierHeader = false;
        foreach ($identifierAliases as $k) {
            if (in_array($k, $headers, true)) {
                $hasIdentifierHeader = true;
                break;
            }
        }
        if (!$hasIdentifierHeader) {
            foreach ($headers as $h) {
                if ($this->isIdentifierHeader($h)) {
                    $hasIdentifierHeader = true;
                    break;
                }
            }
        }
        if (!$hasIdentifierHeader) {
            $msg = 'Kolom identitas dosen tidak ditemukan pada header file. Gunakan salah satu kolom: user_id / nip / email / nidn.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $rows = [];
        $errors = [];

        $nidnUserCache = [];

        foreach ($rowsRaw as $i => $row) {
            if (!is_array($row) || $this->isRowEmpty($row)) {
                continue;
            }
            // original (1-indexed) row number in sheet
            $rowNumber = $headerIdx + 2 + $i;
            $assoc = $this->rowToAssoc($headers, $row);

            $debug = [];
            $user = $this->resolveUserForImport($assoc, $row, $nidnUserCache, $debug);

            // FALLBACK: Jika user tidak ditemukan, gunakan user yang sedang login (Kaprodi)
            if (!$user) {
                $user = $actor;
            }

            try {
                $payload = $this->buildPayload($type, $assoc, $user->id);
                $rows[] = $payload;
            } catch (\InvalidArgumentException $e) {
                $errors[] = ['row' => $rowNumber, 'message' => $e->getMessage()];
            }
        }

        if (count($rows) === 0) {
            $msg = 'Tidak ada data valid untuk diimport.';
            if (count($errors) > 0) {
                $firstErrors = array_slice($errors, 0, 5);
                $errorDetails = array_map(fn($e) => "Baris {$e['row']}: {$e['message']}", $firstErrors);
                $msg .= ' ' . implode('; ', $errorDetails);
                if (count($errors) > 5) {
                    $msg .= ' ... dan ' . (count($errors) - 5) . ' error lainnya.';
                }
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg, 'errors' => $errors], 422);
            }
            return back()->with('error', $msg)->with('import_errors', $errors);
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
            $msg = 'Semua baris dilewati karena data sudah terverifikasi.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg, 'skipped_verified' => $skippedVerified], 422);
            }
            return back()->with('error', $msg)->with('import_errors', $errors);
        }

        $model = $this->modelClass($type);
        $model::upsert($upsertRows, $uniqueBy, $updateColumns);

        $successCount = count($upsertRows);
        $failedCount = count($errors);

        $msg = "Import {$type} selesai. Berhasil diproses: {$successCount}. Dilewati (verified): {$skippedVerified}. Error: {$failedCount}.";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'processed' => $successCount,
                'skipped_verified' => $skippedVerified,
                'failed' => $failedCount,
                'errors' => $errors,
            ]);
        }

        return back()
            ->with('success', $msg)
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
        $category = strtolower(trim((string) ($assoc['kategori'] ?? $assoc['jenis_data'] ?? $assoc['category'] ?? '')));
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

    private function getVal(array $assoc, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            $val = $assoc[$key] ?? null;
            if ($val !== null && trim((string) $val) !== '') {
                return trim((string) $val);
            }
        }
        return $default;
    }

    private function buildPayload(string $type, array $assoc, int $userId): array
    {
        // Flexible column aliases for judul
        $judulAliases = match ($type) {
            'penelitian' => ['judul_penelitian', 'judul', 'title', 'nama_penelitian'],
            'publikasi' => ['judul_publikasi', 'judul', 'title', 'nama_publikasi'],
            'pengmas' => ['judul_pkm', 'judul', 'title', 'nama_pkm', 'judul_pengabdian', 'nama_kegiatan'],
            default => ['judul', 'title'],
        };
        $judulKey = match ($type) {
            'penelitian' => 'judul_penelitian',
            'publikasi' => 'judul_publikasi',
            'pengmas' => 'judul_pkm',
            default => 'judul',
        };

        $judul = $this->getVal($assoc, $judulAliases, '');
        $tahun = $this->getVal($assoc, ['tahun', 'tahun_akademik', 'year', 'ta'], '');
        $semesterRaw = strtolower($this->getVal($assoc, ['semester', 'sem', 'periode'], ''));

        // Normalize semester
        $semesterMap = [
            'ganjil' => 'ganjil',
            'gasal' => 'ganjil',
            'odd' => 'ganjil',
            '1' => 'ganjil',
            'genap' => 'genap',
            'even' => 'genap',
            '2' => 'genap',
        ];
        $semester = $semesterMap[$semesterRaw] ?? null;

        if ($judul === '') {
            throw new \InvalidArgumentException('Judul wajib diisi');
        }
        if ($tahun === '' || !is_numeric($tahun)) {
            throw new \InvalidArgumentException('Tahun akademik wajib diisi (angka)');
        }
        if ($semester === null) {
            throw new \InvalidArgumentException("Semester tidak valid: '{$semesterRaw}'. Gunakan: ganjil, genap, 1, atau 2");
        }

        $base = [
            'user_id' => $userId,
            $judulKey => $judul,
            'tahun' => (int) $tahun,
            'semester' => $semester,
            'status_verifikasi' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
            'catatan_verifikasi' => null,
        ];

        if ($type === 'penelitian') {
            $jenis = strtolower($this->getVal($assoc, ['jenis', 'jenis_penelitian', 'type'], 'mandiri'));
            // Normalize jenis aliases
            $jenisMap = [
                'mandiri' => 'mandiri',
                'hibah_internal' => 'hibah_internal',
                'hibah internal' => 'hibah_internal',
                'internal' => 'hibah_internal',
                'hibah_eksternal' => 'hibah_eksternal',
                'hibah eksternal' => 'hibah_eksternal',
                'eksternal' => 'hibah_eksternal',
                'kerjasama' => 'kerjasama',
                'kerja sama' => 'kerjasama',
            ];
            $jenis = $jenisMap[$jenis] ?? 'mandiri';

            $status = strtolower(trim((string) ($assoc['status'] ?? 'proposal')));
            if (!in_array($status, ['proposal', 'berjalan', 'selesai', 'ditolak'], true)) {
                $status = 'proposal';
            }

            return array_merge($base, [
                'abstrak' => $assoc['abstrak'] ?? null,
                'jenis' => $jenis,
                'sumber_dana' => $assoc['sumber_dana'] ?? null,
                'anggaran' => $this->toDecimal($assoc['anggaran'] ?? $assoc['dana'] ?? null),
                'tanggal_mulai' => $this->toDate($assoc['tanggal_mulai'] ?? null),
                'tanggal_selesai' => $this->toDate($assoc['tanggal_selesai'] ?? null),
                'status' => $status,
                'anggota' => $this->toJsonArray($assoc['anggota'] ?? null),
                'anggota_mahasiswa' => $this->toJsonArray($assoc['anggota_mahasiswa'] ?? $assoc['mahasiswa_terlibat'] ?? null),
                'catatan' => $assoc['catatan'] ?? null,
            ]);
        }

        if ($type === 'publikasi') {
            $jenis = strtolower($this->getVal($assoc, ['jenis', 'jenis_publikasi', 'type'], 'jurnal'));
            // Normalize jenis aliases
            $jenisMap = [
                'jurnal' => 'jurnal',
                'journal' => 'jurnal',
                'prosiding' => 'prosiding',
                'proceeding' => 'prosiding',
                'proceedings' => 'prosiding',
                'buku' => 'buku',
                'book' => 'buku',
                'book_chapter' => 'book_chapter',
                'book chapter' => 'book_chapter',
                'chapter' => 'book_chapter',
                'paten' => 'paten',
                'patent' => 'paten',
                'hki' => 'hki',
            ];
            $jenis = $jenisMap[$jenis] ?? 'jurnal';

            $namaPublikasi = $this->getVal($assoc, ['nama_publikasi', 'nama_jurnal', 'journal_name', 'publisher'], '');
            if ($namaPublikasi === '') {
                $namaPublikasi = $judul; // Use judul as fallback
            }

            $indexing = strtolower(trim((string) ($assoc['indexing'] ?? '')));
            if ($indexing === '') {
                $indexing = null;
            }

            $quartile = strtoupper(trim((string) ($assoc['quartile'] ?? '')));
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
            $jenis = strtolower($this->getVal($assoc, ['jenis', 'jenis_hibah', 'jenis_pengabdian', 'type'], 'mandiri'));
            // Normalize jenis aliases
            $jenisMap = [
                'internal' => 'internal',
                'hibah_internal' => 'internal',
                'hibah internal' => 'internal',
                'eksternal' => 'eksternal',
                'hibah_eksternal' => 'eksternal',
                'hibah eksternal' => 'eksternal',
                'mandiri' => 'mandiri',
            ];
            $jenis = $jenisMap[$jenis] ?? 'mandiri';

            $status = strtolower(trim((string) ($assoc['status'] ?? 'proposal')));
            if (!in_array($status, ['proposal', 'berjalan', 'selesai', 'ditolak'], true)) {
                $status = 'proposal';
            }

            $timAbdimasText = $this->getVal($assoc, ['tim_abdimas', 'tim abdimas', 'tim'], null);
            $parsedTim = $this->parseNameNumberMixed($timAbdimasText);

            $anggotaAbdimasText = $this->getVal($assoc, ['anggota_abdimas', 'anggota abdimas', 'anggota_mahasiswa', 'mahasiswa_terlibat'], null);
            $parsedMhs = $this->parseNameNumberMixed($anggotaAbdimasText);

            // Logic: 
            // 1. Dosen Name -> tim_abdimas
            // 2. Dosen NIP -> dosen_nip (priority: explicit column > parsed numbers)

            // 1. Mahasiswa Name -> anggota_mahasiswa
            // 2. Mahasiswa NIM -> mahasiswa_nim (priority: explicit column > parsed numbers)

            $timAbdimasFinal = count($parsedTim['names']) > 0 ? json_encode($parsedTim['names']) : null;

            $dosenNipExplicit = $this->toJsonArray($this->getVal($assoc, ['dosen_nip', 'nip'], null));
            $dosenNipFinal = $dosenNipExplicit;
            if ($dosenNipFinal === null && count($parsedTim['numbers']) > 0) {
                // No explicit NIP column, use parsed numbers from mixed string
                $dosenNipFinal = json_encode($parsedTim['numbers']);
            }

            $anggotaMhsFinal = count($parsedMhs['names']) > 0 ? json_encode($parsedMhs['names']) : null;

            $mahasiswaNimExplicit = $this->toJsonArray($this->getVal($assoc, ['mahasiswa_nim', 'nim'], null));
            $mahasiswaNimFinal = $mahasiswaNimExplicit;
            if ($mahasiswaNimFinal === null && count($parsedMhs['numbers']) > 0) {
                // No explicit NIM column, use parsed numbers from mixed string
                $mahasiswaNimFinal = json_encode($parsedMhs['numbers']);
            }

            return array_merge($base, [
                'deskripsi' => $this->getVal($assoc, ['deskripsi', 'deskripsi_kegiatan', 'abstrak'], null),
                'jenis_hibah' => $jenis,
                'sumber_dana' => $this->getVal($assoc, ['sumber_dana', 'sumber', 'pendanaan'], null),
                'anggaran' => $this->toDecimal($this->getVal($assoc, ['anggaran', 'dana'], null)),
                'tanggal_mulai' => $this->toDate($this->getVal($assoc, ['tanggal_mulai', 'tgl_mulai', 'start_date'], null)),
                'tanggal_selesai' => $this->toDate($this->getVal($assoc, ['tanggal_selesai', 'tgl_selesai', 'end_date'], null)),
                'skema' => $this->getVal($assoc, ['skema', 'lokasi'], null),
                'mitra' => $this->getVal($assoc, ['mitra'], null),
                'jumlah_peserta' => $this->toInt($this->getVal($assoc, ['jumlah_peserta', 'peserta'], null)),
                'status' => $status,
                'tim_abdimas' => $timAbdimasFinal,
                'dosen_nip' => $dosenNipFinal,
                'anggota_mahasiswa' => $anggotaMhsFinal,
                'mahasiswa_nim' => $mahasiswaNimFinal,
                'catatan' => $this->getVal($assoc, ['catatan'], null),

                // Optional analytics/extra columns (if provided)
                'sdg' => $this->getVal($assoc, ['sdg'], null),
                'kesesuaian_roadmap_kk' => $this->getVal($assoc, ['kesesuaian_roadmap_kk', 'roadmap_kk'], null),
                'tipe_pendanaan' => $this->getVal($assoc, ['tipe_pendanaan'], null),
                'status_kegiatan' => $this->getVal($assoc, ['status_kegiatan'], null),
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
            'penelitian' => ['user_id', 'judul_penelitian', 'tahun', 'semester'],
            'publikasi' => ['user_id', 'judul_publikasi', 'jenis', 'tahun', 'semester'],
            'pengmas' => ['user_id', 'judul_pkm', 'tahun', 'semester'],
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
            $parts[] = (string) ($payload[$col] ?? '');
        }
        return implode('|', $parts);
    }

    /**
     * Parse mixed string "Name Number" or "Number Name" into separate arrays.
     * Splitting logic:
     * - Digits -> put to 'numbers'
     * - Letters/Text -> put to 'names'
     */
    private function parseNameNumberMixed($input): array
    {
        $names = [];
        $numbers = [];

        if ($input === null || trim((string) $input) === '') {
            return ['names' => [], 'numbers' => []];
        }

        // 1. Normalize delimiters (comma, newline, semicolon) to newline
        $normalized = str_replace([',', ';'], "\n", (string) $input);

        // 2. Split by newline to get individual person entries
        $lines = explode("\n", $normalized);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '')
                continue;

            // 3. Extract all digits as Number (NIP/NIM)
            // Use regex to look for sequence of digits (at least 5 to be safe identifier, or just ANY digits?)
            // User request: "AMBIL ANGKA NYA SAJA".
            // Let's assume NIP/NIM is a contiguous block of digits. 
            // If there are multiple blocks (e.g. "123 456"), we might need to join them or pick the longest.
            // Let's pick all digit sequences.
            preg_match_all('/\d+/', $line, $matches);
            $digitsFound = implode('', $matches[0] ?? []); // Join all digits found (e.g. "1990 01 01" -> "19900101")

            if ($digitsFound !== '') {
                $numbers[] = $digitsFound;
            }

            // 4. Extract Letters as Name
            // Remove digits and common number-separators
            $namePart = preg_replace('/[\d]+/', '', $line);
            // Clean up: remove () [] - if they were wrapping the number
            $namePart = str_replace(['(', ')', '[', ']', ':', '-'], ' ', $namePart);
            // Trim whitespace
            $namePart = trim(preg_replace('/\s+/', ' ', $namePart));

            if ($namePart !== '') {
                $names[] = $namePart;
            }
        }

        // Return distinct values
        return [
            'names' => array_values(array_unique($names)),
            'numbers' => array_values(array_unique($numbers)),
        ];
    }
}
