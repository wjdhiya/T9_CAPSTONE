<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\PengabdianMasyarakatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard - using our custom DashboardController
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Penelitian routes
    Route::resource('penelitian', PenelitianController::class);
    Route::post('penelitian/{penelitian}/verify', [PenelitianController::class, 'verify'])
        ->name('penelitian.verify')
        ->middleware('role:super_admin,kaprodi');
    Route::post('/penelitian', [PenelitianController::class, 'store'])->name('penelitian.store');

    // Publikasi routes
    Route::resource('publikasi', PublikasiController::class);
    Route::post('publikasi/{publikasi}/verify', [PublikasiController::class, 'verify'])
        ->name('publikasi.verify')
        ->middleware('role:super_admin,kaprodi');

    // Pengabdian Masyarakat routes
    Route::resource('pengmas', PengabdianMasyarakatController::class);
    Route::post('pengmas/{pengma}/verify', [PengabdianMasyarakatController::class, 'verify'])
        ->name('pengmas.verify')
        ->middleware('role:super_admin,kaprodi');

    // Reports routes (Admin/Kaprodi only)
    Route::middleware('role:super_admin,kaprodi')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/reports/productivity', [ReportController::class, 'productivity'])->name('reports.productivity');
    });

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes (from Breeze)
require __DIR__.'/auth.php';
