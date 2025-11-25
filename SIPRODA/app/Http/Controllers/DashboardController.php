<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\PengabdianMasyarakat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Base queries
        $penelitianQuery = Penelitian::query();
        $publikasiQuery  = Publikasi::query();
        $pengmasQuery    = PengabdianMasyarakat::query();

        // If logged-in user is dosen, limit to their own records
        if ($isDosen) {
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

        return view('dashboard', compact('stats'));
    }
}

