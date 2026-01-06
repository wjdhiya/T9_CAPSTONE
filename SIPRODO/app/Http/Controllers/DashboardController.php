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
                'total'    => (clone $penelitianQuery)->count(),
                'verified' => (clone $penelitianQuery)->where('status_verifikasi', 'verified')->count(),
            ],
            'publikasi' => [
                'total'    => (clone $publikasiQuery)->count(),
                'verified' => (clone $publikasiQuery)->where('status_verifikasi', 'verified')->count(),
            ],
            'pengmas' => [
                'total'    => (clone $pengmasQuery)->count(),
                'verified' => (clone $pengmasQuery)->where('status_verifikasi', 'verified')->count(),
            ],
        ];

        // Role-specific dashboard widgets
        $topLecturers = [];
        $verificationQueue = [];
        
        if ($isKaprodi) {
            // Get top 5 most active lecturers in current semester
            $topLecturers = User::where('role', User::ROLE_DOSEN)
                ->where('is_active', true)
                ->select('id', 'name', 'nidn')
                ->withCount(['penelitian as total_penelitian' => function($query) use ($semesterStart, $semesterEnd) {
                    $query->whereBetween('created_at', [$semesterStart, $semesterEnd]);
                }, 'publikasi as total_publikasi' => function($query) use ($semesterStart, $semesterEnd) {
                    $query->whereBetween('created_at', [$semesterStart, $semesterEnd]);
                }, 'pengabdianMasyarakat as total_pengmas' => function($query) use ($semesterStart, $semesterEnd) {
                    $query->whereBetween('created_at', [$semesterStart, $semesterEnd]);
                }])
                ->orderByRaw('(total_penelitian + total_publikasi + total_pengmas) DESC')
                ->take(5)
                ->get()
                ->map(function($user) {
                    $user->total_activities = $user->total_penelitian + $user->total_publikasi + $user->total_pengmas;
                    return $user;
                });
        }

        if ($isAdmin) {
            // Get pending verifications
            $verificationQueue = [
                'penelitian' => Penelitian::where('status_verifikasi', 'pending')->count(),
                'publikasi' => Publikasi::where('status_verifikasi', 'pending')->count(),
                'pengmas' => PengabdianMasyarakat::where('status_verifikasi', 'pending')->count(),
            ];
        }

        return view('dashboard', compact('stats', 'topLecturers', 'verificationQueue', 'isAdmin', 'isKaprodi', 'currentSemester', 'currentYear'));
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

        $penelitianQuery = Penelitian::query()
            ->where('tahun', 'like', $tahun . '%')
            ->where('semester', $semester);

        $publikasiQuery = Publikasi::query()
            ->where('tahun', 'like', $tahun . '%')
            ->where('semester', $semester);

        $pengmasQuery = PengabdianMasyarakat::query()
            ->where('tahun', 'like', $tahun . '%')
            ->where('semester', $semester);

        $stats = [
            'penelitian' => [
                'total' => (clone $penelitianQuery)->count(),
                'verified' => (clone $penelitianQuery)->where('status_verifikasi', 'verified')->count(),
                'pending' => (clone $penelitianQuery)->where('status_verifikasi', 'pending')->count(),
                'rejected' => (clone $penelitianQuery)->where('status_verifikasi', 'rejected')->count(),
            ],
            'publikasi' => [
                'total' => (clone $publikasiQuery)->count(),
                'verified' => (clone $publikasiQuery)->where('status_verifikasi', 'verified')->count(),
                'pending' => (clone $publikasiQuery)->where('status_verifikasi', 'pending')->count(),
                'rejected' => (clone $publikasiQuery)->where('status_verifikasi', 'rejected')->count(),
            ],
            'pengmas' => [
                'total' => (clone $pengmasQuery)->count(),
                'verified' => (clone $pengmasQuery)->where('status_verifikasi', 'verified')->count(),
                'pending' => (clone $pengmasQuery)->where('status_verifikasi', 'pending')->count(),
                'rejected' => (clone $pengmasQuery)->where('status_verifikasi', 'rejected')->count(),
            ],
        ];

        $topLecturers = User::where('role', User::ROLE_DOSEN)
            ->where('is_active', true)
            ->select('id', 'name', 'nidn')
            ->withCount([
                'penelitian as total_penelitian' => function ($query) use ($tahun, $semester) {
                    $query->where('tahun', 'like', $tahun . '%')
                        ->where('semester', $semester);
                },
                'publikasi as total_publikasi' => function ($query) use ($tahun, $semester) {
                    $query->where('tahun', 'like', $tahun . '%')
                        ->where('semester', $semester);
                },
                'pengabdianMasyarakat as total_pengmas' => function ($query) use ($tahun, $semester) {
                    $query->where('tahun', 'like', $tahun . '%')
                        ->where('semester', $semester);
                },
            ])
            ->orderByRaw('(total_penelitian + total_publikasi + total_pengmas) DESC')
            ->take(5)
            ->get()
            ->map(function ($u) {
                return [
                    'name' => $u->name,
                    'nidn' => $u->nidn,
                    'total_penelitian' => (int) ($u->total_penelitian ?? 0),
                    'total_publikasi' => (int) ($u->total_publikasi ?? 0),
                    'total_pengmas' => (int) ($u->total_pengmas ?? 0),
                ];
            });

        return response()->json([
            'tahun' => (int) $validated['tahun'],
            'semester' => $semester,
            'stats' => $stats,
            'topLecturers' => $topLecturers,
        ]);
    }
}

