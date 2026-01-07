<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\PengabdianMasyarakat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    /**
     * Show dashboard with stats for penelitian, publikasi and pengabdian masyarakat.
     */
    public function index(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        $isDosen = $user && method_exists($user, 'isDosen') && $user->isDosen();
        $isAdmin = $user && $user->isAdmin();
        $isKaprodi = $user && $user->isKaprodi();

        // Base queries
        $penelitianQuery = Penelitian::query();
        $publikasiQuery = Publikasi::query();
        $pengmasQuery = PengabdianMasyarakat::query();

        // Get current semester (1: Jan-Jun, 2: Jul-Dec)
        $currentMonth = (int) date('n');
        $currentYear = (int) date('Y');
        $currentSemester = $currentMonth <= 6 ? 1 : 2;
        $semesterStart = $currentSemester === 1 ? "$currentYear-01-01" : "$currentYear-07-01";
        $semesterEnd = $currentSemester === 1 ? "$currentYear-06-30" : "$currentYear-12-31";

        // If logged-in user is dosen, limit to their own records
        if ($isDosen && !$isAdmin && !$isKaprodi) {
            $penelitianQuery->where('user_id', $user->id);
            $publikasiQuery->where('user_id', $user->id);
            $pengmasQuery->where('user_id', $user->id);
        }

        // Build stats
        $stats = [
            'penelitian' => [
                'total' => (clone $penelitianQuery)->count(),
                'verified' => (clone $penelitianQuery)->where('status_verifikasi', 'verified')->count(),
            ],
            'publikasi' => [
                'total' => (clone $publikasiQuery)->count(),
                'verified' => (clone $publikasiQuery)->where('status_verifikasi', 'verified')->count(),
            ],
            'pengmas' => [
                'total' => (clone $pengmasQuery)->count(),
                'verified' => (clone $pengmasQuery)->where('status_verifikasi', 'verified')->count(),
            ],
        ];

        // Role-specific dashboard widgets
        $topLecturers = [];
        $verificationQueue = [];
        $kaprodiNotifications = [
            'penelitian' => 0,
            'publikasi' => 0,
            'pengmas' => 0,
        ];

        if ($isKaprodi) {
            // Get top 5 most active lecturers in current semester
            $topLecturers = User::where('role', User::ROLE_DOSEN)
                ->where('is_active', true)
                ->select('id', 'name', 'nip')
                ->withCount([
                    'penelitian as total_penelitian' => function ($query) use ($semesterStart, $semesterEnd) {
                        $query->whereBetween('created_at', [$semesterStart, $semesterEnd]);
                    },
                    'publikasi as total_publikasi' => function ($query) use ($semesterStart, $semesterEnd) {
                        $query->whereBetween('created_at', [$semesterStart, $semesterEnd]);
                    },
                    'pengabdianMasyarakat as total_pengmas' => function ($query) use ($semesterStart, $semesterEnd) {
                        $query->whereBetween('created_at', [$semesterStart, $semesterEnd]);
                    }
                ])
                ->orderByRaw('(total_penelitian + total_publikasi + total_pengmas) DESC')
                ->take(5)
                ->get()
                ->map(function ($user) {
                    $user->total_activities = $user->total_penelitian + $user->total_publikasi + $user->total_pengmas;
                    return $user;
                });

            // Count newly verified items since Kaprodi last viewed each category
            $sincePenelitian = $user->kaprodi_seen_penelitian_at ?? $user->created_at;
            $sincePublikasi = $user->kaprodi_seen_publikasi_at ?? $user->created_at;
            $sincePengmas = $user->kaprodi_seen_pengmas_at ?? $user->created_at;

            $kaprodiNotifications['penelitian'] = Penelitian::where('status_verifikasi', 'verified')
                ->whereNotNull('verified_at')
                ->where('verified_at', '>', $sincePenelitian)
                ->count();

            $kaprodiNotifications['publikasi'] = Publikasi::where('status_verifikasi', 'verified')
                ->whereNotNull('verified_at')
                ->where('verified_at', '>', $sincePublikasi)
                ->count();

            $kaprodiNotifications['pengmas'] = PengabdianMasyarakat::where('status_verifikasi', 'verified')
                ->whereNotNull('verified_at')
                ->where('verified_at', '>', $sincePengmas)
                ->count();
        }

        if ($isAdmin) {
            // Get pending verifications
            $verificationQueue = [
                'penelitian' => Penelitian::where('status_verifikasi', 'pending')->count(),
                'publikasi' => Publikasi::where('status_verifikasi', 'pending')->count(),
                'pengmas' => PengabdianMasyarakat::where('status_verifikasi', 'pending')->count(),
            ];
        }

        return view('dashboard', compact('stats', 'topLecturers', 'verificationQueue', 'kaprodiNotifications', 'isAdmin', 'isKaprodi', 'currentSemester', 'currentYear'));
    }

    public function kaprodiSummary(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!($user && $user->isKaprodi())) {
            abort(403);
        }

        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'semester' => ['required', Rule::in(['ganjil', 'genap'])],
        ]);

        $tahun = (string) $validated['tahun'];
        $semester = $validated['semester'];

        // Queries for Global Stats (ALL data, no year/semester filter)
        $statsQuery = [
            'penelitian' => Penelitian::query(),
            'publikasi' => Publikasi::query(),
            'pengmas' => PengabdianMasyarakat::query(),
        ];

        $stats = [
            'penelitian' => [
                'total' => (clone $statsQuery['penelitian'])->count(),
                'verified' => (clone $statsQuery['penelitian'])->where('status_verifikasi', 'verified')->count(),
                'pending' => (clone $statsQuery['penelitian'])->where('status_verifikasi', 'pending')->count(),
                'rejected' => (clone $statsQuery['penelitian'])->where('status_verifikasi', 'rejected')->count(),
            ],
            'publikasi' => [
                'total' => (clone $statsQuery['publikasi'])->count(),
                'verified' => (clone $statsQuery['publikasi'])->where('status_verifikasi', 'verified')->count(),
                'pending' => (clone $statsQuery['publikasi'])->where('status_verifikasi', 'pending')->count(),
                'rejected' => (clone $statsQuery['publikasi'])->where('status_verifikasi', 'rejected')->count(),
            ],
            'pengmas' => [
                'total' => (clone $statsQuery['pengmas'])->count(),
                'verified' => (clone $statsQuery['pengmas'])->where('status_verifikasi', 'verified')->count(),
                'pending' => (clone $statsQuery['pengmas'])->where('status_verifikasi', 'pending')->count(),
                'rejected' => (clone $statsQuery['pengmas'])->where('status_verifikasi', 'rejected')->count(),
            ],
        ];

        // --- Calculate Top Lecturers by Member Names ---

        // 1. Get all Lecturers Maps for lookup
        $allLecturers = User::where('role', 'dosen')->get();
        $lecturersByName = $allLecturers->keyBy(fn($item) => strtoupper(trim($item->name)));
        $lecturersByNip = $allLecturers->whereNotNull('nip')->keyBy(fn($item) => (string) trim($item->nip));

        // Structure: ['KEY' => ['name' => 'Real Name', 'nip' => '...', 'total_penelitian' => 0, ...]]
        $scores = [];

        // Helper to process names
        $processNames = function ($names, $nips, $type) use (&$scores, $lecturersByName, $lecturersByNip) {
            // Robust parsing: Handle if Cast failed or if data is raw string
            if (is_string($names)) {
                $decoded = json_decode($names, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                    $names = $decoded;
                else
                    $names = array_map('trim', explode(',', $names)); // Fallback CSV
            }
            if (empty($names))
                return;

            if (is_string($nips)) {
                $decoded = json_decode($nips, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                    $nips = $decoded;
                else
                    $nips = array_map('trim', explode(',', $nips));
            }
            if (empty($nips))
                $nips = [];

            // We need to track unique people in THIS activity to avoid double counting
            $seenInActivity = [];

            foreach ($names as $index => $rawName) {
                if (empty($rawName))
                    continue;

                // 1. Clean Name
                // Remove " NIP" suffix case-insensitive
                $cleanName = trim(preg_replace('/ NIP$/i', '', trim($rawName)));
                if (empty($cleanName) || $cleanName === '-')
                    continue;

                // 2. Get provided NIP if any
                $providedNip = isset($nips[$index]) && !empty($nips[$index]) ? trim($nips[$index]) : null;

                // 3. Resolve User Identity
                $realName = ucwords(strtolower($cleanName));
                $finalNip = $providedNip ?? '-';
                $key = strtoupper($cleanName); // Default key

                // Try Lookup by NIP first (most reliable)
                if ($providedNip && $user = $lecturersByNip->get($providedNip)) {
                    $realName = $user->name;
                    $finalNip = $user->nip;
                    $key = 'NIP_' . $finalNip;
                }
                // Try Lookup by Name
                elseif ($user = $lecturersByName->get(strtoupper($cleanName))) {
                    $realName = $user->name;
                    $finalNip = $user->nip;
                    $key = 'NIP_' . $finalNip;
                }

                // If we haven't seen this person in this activity yet
                if (in_array($key, $seenInActivity))
                    continue;
                $seenInActivity[] = $key;

                // Initialize if not exists
                if (!isset($scores[$key])) {
                    $scores[$key] = [
                        'name' => $realName,
                        'nip' => $finalNip,
                        'total_penelitian' => 0,
                        'total_publikasi' => 0,
                        'total_pengmas' => 0,
                    ];
                }

                // Increment
                if ($type === 'penelitian')
                    $scores[$key]['total_penelitian']++;
                elseif ($type === 'publikasi')
                    $scores[$key]['total_publikasi']++;
                elseif ($type === 'pengmas')
                    $scores[$key]['total_pengmas']++;
            }
        };

        // 2. Process Penelitian
        $penelitianItems = Penelitian::where('tahun', 'like', $tahun . '%')
            ->where('semester', $semester)
            ->get();

        foreach ($penelitianItems as $item) {
            $members = $item->anggota;
            // Penelitian doesn't have a separate NIP array in DB usually, but we check model
            // Model: 'anggota' => SafeArray. No 'anggota_nip'.
            $processNames($members, [], 'penelitian');
        }

        // 3. Process Publikasi
        $publikasiItems = Publikasi::where('tahun', 'like', $tahun . '%')
            ->where('semester', $semester)
            ->get();

        foreach ($publikasiItems as $item) {
            $members = $item->penulis;
            $processNames($members, [], 'publikasi');
        }

        // 4. Process Pengmas
        $pengmasItems = PengabdianMasyarakat::where('tahun', 'like', $tahun . '%')
            ->where('semester', $semester)
            ->get();

        foreach ($pengmasItems as $item) {
            // Pengmas has PARALLEL arrays: tim_abdimas and dosen_nip
            $members = $item->tim_abdimas;
            $nips = $item->dosen_nip;
            $processNames($members, $nips, 'pengmas');
        }

        // 5. Sort and Limit
        $topLecturers = collect(array_values($scores))
            ->map(function ($item) {
                $item['total'] = $item['total_penelitian'] + $item['total_publikasi'] + $item['total_pengmas'];
                return (object) $item;
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        return response()->json([
            'tahun' => (int) $validated['tahun'],
            'semester' => $semester,
            'stats' => $stats,
            'topLecturers' => $topLecturers,
        ]);
    }
}

