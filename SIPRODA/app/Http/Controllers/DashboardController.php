<?php

namespace App\Http\Controllers;

use App\Models\Penelitian;
use App\Models\Publikasi;
use App\Models\PengabdianMasyarakat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistics based on role
        if ($user->isSuperAdmin() || $user->isKaprodi()) {
            $stats = $this->getAdminStats();
            $charts = $this->getChartData();
            $recentActivities = $this->getRecentActivities();

            return view('dashboard', compact('stats', 'charts', 'recentActivities'));
        } else {
            $stats = $this->getDosenStats($user->id);
            $myData = $this->getMyData($user->id);

            return view('dashboard', compact('stats', 'myData'));
        }
    }
    
    private function getAdminStats()
    {
        $currentYear = date('Y');
        $currentSemester = (date('n') <= 6) ? 'genap' : 'ganjil';
        
        return [
            'total_dosen' => User::where('role', 'dosen')->where('is_active', true)->count(),
            'total_penelitian' => Penelitian::whereYear('tahun', $currentYear)->count(),
            'total_publikasi' => Publikasi::whereYear('tahun', $currentYear)->count(),
            'total_pengmas' => PengabdianMasyarakat::whereYear('tahun', $currentYear)->count(),
            
            'penelitian_verified' => Penelitian::whereYear('tahun', $currentYear)
                ->where('status_verifikasi', 'verified')->count(),
            'penelitian_pending' => Penelitian::whereYear('tahun', $currentYear)
                ->where('status_verifikasi', 'pending')->count(),
                
            'publikasi_verified' => Publikasi::whereYear('tahun', $currentYear)
                ->where('status_verifikasi', 'verified')->count(),
            'publikasi_pending' => Publikasi::whereYear('tahun', $currentYear)
                ->where('status_verifikasi', 'pending')->count(),
                
            'pengmas_verified' => PengabdianMasyarakat::whereYear('tahun', $currentYear)
                ->where('status_verifikasi', 'verified')->count(),
            'pengmas_pending' => PengabdianMasyarakat::whereYear('tahun', $currentYear)
                ->where('status_verifikasi', 'pending')->count(),
                
            // Productivity ratios
            'ratio_penelitian' => $this->calculateRatio('penelitian', $currentYear),
            'ratio_publikasi' => $this->calculateRatio('publikasi', $currentYear),
            'ratio_pengmas' => $this->calculateRatio('pengmas', $currentYear),
        ];
    }
    
    private function getDosenStats($userId)
    {
        $currentYear = date('Y');
        
        return [
            'total_penelitian' => Penelitian::where('user_id', $userId)->count(),
            'total_publikasi' => Publikasi::where('user_id', $userId)->count(),
            'total_pengmas' => PengabdianMasyarakat::where('user_id', $userId)->count(),
            
            'penelitian_tahun_ini' => Penelitian::where('user_id', $userId)
                ->whereYear('tahun', $currentYear)->count(),
            'publikasi_tahun_ini' => Publikasi::where('user_id', $userId)
                ->whereYear('tahun', $currentYear)->count(),
            'pengmas_tahun_ini' => PengabdianMasyarakat::where('user_id', $userId)
                ->whereYear('tahun', $currentYear)->count(),
                
            'penelitian_verified' => Penelitian::where('user_id', $userId)
                ->where('status_verifikasi', 'verified')->count(),
            'publikasi_verified' => Publikasi::where('user_id', $userId)
                ->where('status_verifikasi', 'verified')->count(),
            'pengmas_verified' => PengabdianMasyarakat::where('user_id', $userId)
                ->where('status_verifikasi', 'verified')->count(),
        ];
    }
    
    private function calculateRatio($type, $year)
    {
        $totalDosen = User::where('role', 'dosen')->where('is_active', true)->count();
        
        if ($totalDosen == 0) return 0;
        
        $total = 0;
        switch ($type) {
            case 'penelitian':
                $total = Penelitian::whereYear('tahun', $year)
                    ->where('status_verifikasi', 'verified')->count();
                break;
            case 'publikasi':
                $total = Publikasi::whereYear('tahun', $year)
                    ->where('status_verifikasi', 'verified')->count();
                break;
            case 'pengmas':
                $total = PengabdianMasyarakat::whereYear('tahun', $year)
                    ->where('status_verifikasi', 'verified')->count();
                break;
        }
        
        return round($total / $totalDosen, 2);
    }
    
    private function getChartData()
    {
        $currentYear = date('Y');
        $years = range($currentYear - 4, $currentYear);
        
        $penelitianData = [];
        $publikasiData = [];
        $pengmasData = [];
        
        foreach ($years as $year) {
            $penelitianData[] = Penelitian::whereYear('tahun', $year)
                ->where('status_verifikasi', 'verified')->count();
            $publikasiData[] = Publikasi::whereYear('tahun', $year)
                ->where('status_verifikasi', 'verified')->count();
            $pengmasData[] = PengabdianMasyarakat::whereYear('tahun', $year)
                ->where('status_verifikasi', 'verified')->count();
        }
        
        return [
            'years' => $years,
            'penelitian' => $penelitianData,
            'publikasi' => $publikasiData,
            'pengmas' => $pengmasData,
        ];
    }
    
    private function getRecentActivities()
    {
        $penelitian = Penelitian::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'penelitian',
                    'title' => $item->judul,
                    'user' => $item->user->name,
                    'status' => $item->status_verifikasi,
                    'date' => $item->created_at,
                ];
            });
            
        $publikasi = Publikasi::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'publikasi',
                    'title' => $item->judul,
                    'user' => $item->user->name,
                    'status' => $item->status_verifikasi,
                    'date' => $item->created_at,
                ];
            });
            
        $pengmas = PengabdianMasyarakat::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'pengmas',
                    'title' => $item->judul,
                    'user' => $item->user->name,
                    'status' => $item->status_verifikasi,
                    'date' => $item->created_at,
                ];
            });
            
        return $penelitian->concat($publikasi)
            ->concat($pengmas)
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }
    
    private function getMyData($userId)
    {
        return [
            'penelitian' => Penelitian::where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get(),
            'publikasi' => Publikasi::where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get(),
            'pengmas' => PengabdianMasyarakat::where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get(),
        ];
    }
}

