<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\LpjController;
use App\Http\Controllers\Api\PrestasiController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\InformasiController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MonitoringController;

/**
 * TOP KEMA Telkom - Organisasi dan Prestasi Kemahasiswaan
 * REST API v1
 * 
 * Base URL: /api/v1
 * Authentication: Bearer Token (Laravel Sanctum)
 */

Route::prefix('v1')->group(function () {

    // ============================================================
    // AUTHENTICATION ROUTES (Public)
    // ============================================================
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    });

    // ============================================================
    // PROTECTED ROUTES (Require Authentication)
    // ============================================================
    Route::middleware('auth:sanctum')->group(function () {

        // Authentication Routes
        Route::prefix('auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('generate-token', [AuthController::class, 'generateToken'])->name('auth.generate-token');
            Route::get('me', [AuthController::class, 'me'])->name('auth.me');
        });

        // ========================================================
        // PROPOSAL KEGIATAN ROUTES
        // ========================================================
        Route::prefix('proposal')->name('proposal.')->group(function () {
            Route::get('/', [ProposalController::class, 'index'])->name('index');
            Route::post('/', [ProposalController::class, 'store'])->name('store');
            Route::get('/{proposal}', [ProposalController::class, 'show'])->name('show')->where('proposal', '[0-9]+');
            Route::put('/{proposal}', [ProposalController::class, 'update'])->name('update')->where('proposal', '[0-9]+');
            Route::delete('/{proposal}', [ProposalController::class, 'destroy'])->name('destroy')->where('proposal', '[0-9]+');
            Route::get('/{proposal}/status', [ProposalController::class, 'checkStatus'])->name('status')->where('proposal', '[0-9]+');
            Route::post('/{proposal}/revisi', [ProposalController::class, 'submitRevision'])->name('revisi')->where('proposal', '[0-9]+');
            Route::patch('/{proposal}/verifikasi', [ProposalController::class, 'verify'])->middleware('role:Kemahasiswaan')->name('verify')->where('proposal', '[0-9]+');
        });

        // ========================================================
        // LPJ KEGIATAN ROUTES
        // ========================================================
        Route::prefix('lpj')->name('lpj.')->group(function () {
            Route::get('/', [LpjController::class, 'index'])->name('index');
            Route::post('/', [LpjController::class, 'store'])->name('store');
            Route::get('/{lpj}', [LpjController::class, 'show'])->name('show')->where('lpj', '[0-9]+');
            Route::post('/{lpj}/revisi', [LpjController::class, 'submitRevision'])->name('revisi')->where('lpj', '[0-9]+');
            Route::patch('/{lpj}/verifikasi', [LpjController::class, 'verify'])->middleware('role:Kemahasiswaan')->name('verify')->where('lpj', '[0-9]+');
        });

        // ========================================================
        // PRESTASI ROUTES
        // ========================================================
        Route::prefix('prestasi')->name('prestasi.')->group(function () {
            Route::get('/', [PrestasiController::class, 'index'])->name('index');
            Route::post('/', [PrestasiController::class, 'store'])->middleware('role:Mahasiswa')->name('store');
            Route::get('/{prestasi}', [PrestasiController::class, 'show'])->name('show')->where('prestasi', '[0-9]+');
            Route::get('/{prestasi}/status', [PrestasiController::class, 'checkStatus'])->name('status')->where('prestasi', '[0-9]+');
            Route::patch('/{prestasi}/verifikasi', [PrestasiController::class, 'verify'])->middleware('role:Kemahasiswaan')->name('verify')->where('prestasi', '[0-9]+');
            Route::post('/{prestasi}/anggota', [PrestasiController::class, 'addAnggota'])->name('add-anggota')->where('prestasi', '[0-9]+');
            Route::post('/{prestasi}/dosen', [PrestasiController::class, 'addDosen'])->name('add-dosen')->where('prestasi', '[0-9]+');
        });

        // ========================================================
        // TEMPLATE DOKUMEN ROUTES
        // ========================================================
        Route::prefix('template')->name('template.')->group(function () {
            Route::get('/', [TemplateController::class, 'index'])->name('index');
            Route::post('/', [TemplateController::class, 'store'])->middleware('role:Kemahasiswaan')->name('store');
            Route::get('/{template}', [TemplateController::class, 'show'])->name('show')->where('template', '[0-9]+');
            Route::put('/{template}', [TemplateController::class, 'update'])->middleware('role:Kemahasiswaan')->name('update')->where('template', '[0-9]+');
            Route::delete('/{template}', [TemplateController::class, 'destroy'])->middleware('role:Kemahasiswaan')->name('destroy')->where('template', '[0-9]+');
            Route::get('/{template}/download', [TemplateController::class, 'download'])->name('download')->where('template', '[0-9]+');
        });

        // ========================================================
        // INFORMASI KEGIATAN ROUTES
        // ========================================================
        Route::prefix('informasi')->name('informasi.')->group(function () {
            Route::get('/', [InformasiController::class, 'index'])->name('index');
            Route::post('/', [InformasiController::class, 'store'])->name('store');
            Route::get('/{informasi}', [InformasiController::class, 'show'])->name('show')->where('informasi', '[0-9]+');
            Route::put('/{informasi}', [InformasiController::class, 'update'])->name('update')->where('informasi', '[0-9]+');
            Route::delete('/{informasi}', [InformasiController::class, 'destroy'])->name('destroy')->where('informasi', '[0-9]+');
        });

        // ========================================================
        // USER MANAGEMENT ROUTES (Super Admin + Kemahasiswaan only)
        // ========================================================
        Route::prefix('users')->name('users.')->middleware('role:Super Admin,Kemahasiswaan')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show')->where('user', '[0-9]+');
            Route::put('/{user}', [UserController::class, 'update'])->name('update')->where('user', '[0-9]+');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy')->where('user', '[0-9]+');
            Route::patch('/{user}/toggle-akses', [UserController::class, 'toggleAccess'])->name('toggle-access')->where('user', '[0-9]+');
            Route::patch('/{user}/assign-role', [UserController::class, 'assignRole'])->name('assign-role')->where('user', '[0-9]+');
        });

        // ========================================================
        // MONITORING ROUTES (Super Admin + DPMBEM + Kemahasiswaan only)
        // ========================================================
        Route::prefix('monitoring')->name('monitoring.')->middleware('role:Super Admin,Kemahasiswaan,DPMBEM')->group(function () {
            Route::get('/kegiatan', [MonitoringController::class, 'activities'])->name('activities');
            Route::get('/anggaran', [MonitoringController::class, 'budgetTransparency'])->name('anggaran');
            Route::get('/lpj', [MonitoringController::class, 'lpjList'])->name('lpj');
            Route::get('/kegiatan/{proposal}', [MonitoringController::class, 'activityDetail'])->name('activity-detail')->where('proposal', '[0-9]+');
            Route::get('/statistics', [MonitoringController::class, 'statistics'])->name('statistics');
        });

    });

    // ============================================================
    // FALLBACK ROUTE
    // ============================================================
    Route::fallback(function () {
        return response()->json([
            'status' => 'error',
            'message' => 'Endpoint not found',
        ], 404);
    });

});

// ============================================================
// HEALTH CHECK
// ============================================================
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now(),
    ]);
});
