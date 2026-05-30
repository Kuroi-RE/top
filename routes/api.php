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
use App\Http\Controllers\Api\DokumenPrestasiController;
use App\Http\Controllers\Api\CetakPrestasiController;

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
    // PUBLIC INFORMASI ROUTES
    // ============================================================
    Route::prefix('informasi')->name('informasi.')->group(function () {
        Route::get('/', [InformasiController::class, 'index'])->name('index');
        Route::get('/{informasi}', [InformasiController::class, 'show'])->name('show')->where('informasi', '[0-9]+');
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
            Route::get('/', [ProposalController::class, 'index'])->middleware('permission:View Proposal Kegiatan')->name('index');
            Route::post('/', [ProposalController::class, 'store'])->middleware('permission:Create Proposal Kegiatan')->name('store');
            Route::get('/{proposal}', [ProposalController::class, 'show'])->middleware('permission:View Proposal Kegiatan')->name('show')->where('proposal', '[0-9]+');
            Route::put('/{proposal}', [ProposalController::class, 'update'])->middleware('permission:Edit Proposal Kegiatan')->name('update')->where('proposal', '[0-9]+');
            Route::delete('/{proposal}', [ProposalController::class, 'destroy'])->middleware('permission:Delete Proposal Kegiatan')->name('destroy')->where('proposal', '[0-9]+');
            Route::get('/{proposal}/status', [ProposalController::class, 'checkStatus'])->middleware('permission:View Proposal Kegiatan')->name('status')->where('proposal', '[0-9]+');
            Route::post('/{proposal}/revisi', [ProposalController::class, 'submitRevision'])->middleware('permission:Create Proposal Kegiatan|Edit Proposal Kegiatan')->name('revisi')->where('proposal', '[0-9]+');
            Route::patch('/{proposal}/verifikasi', [ProposalController::class, 'verify'])->middleware('permission:Approve Proposal Kegiatan|Reject Proposal Kegiatan')->name('verify')->where('proposal', '[0-9]+');
        });

        // ========================================================
        // LPJ KEGIATAN ROUTES
        // ========================================================
        Route::prefix('lpj')->name('lpj.')->group(function () {
            Route::get('/', [LpjController::class, 'index'])->middleware('permission:View LPJ Kegiatan')->name('index');
            Route::post('/', [LpjController::class, 'store'])->middleware('permission:Create LPJ Kegiatan')->name('store');
            Route::get('/{lpj}', [LpjController::class, 'show'])->middleware('permission:View LPJ Kegiatan')->name('show')->where('lpj', '[0-9]+');
            Route::post('/{lpj}/revisi', [LpjController::class, 'submitRevision'])->middleware('permission:Create LPJ Kegiatan|Edit LPJ Kegiatan')->name('revisi')->where('lpj', '[0-9]+');
            Route::patch('/{lpj}/verifikasi', [LpjController::class, 'verify'])->middleware('permission:Approve LPJ Kegiatan|Reject LPJ Kegiatan')->name('verify')->where('lpj', '[0-9]+');
        });

        // ========================================================
        // PRESTASI ROUTES
        // ========================================================
        Route::prefix('prestasi')->name('prestasi.')->group(function () {
            Route::get('/', [PrestasiController::class, 'index'])->middleware('permission:View Prestasi')->name('index');
            Route::post('/', [PrestasiController::class, 'store'])->middleware('permission:Create Prestasi')->name('store');
            Route::get('/{prestasi}', [PrestasiController::class, 'show'])->middleware('permission:View Prestasi')->name('show')->where('prestasi', '[0-9]+');
            Route::get('/{prestasi}/status', [PrestasiController::class, 'checkStatus'])->middleware('permission:View Prestasi')->name('status')->where('prestasi', '[0-9]+');
            Route::patch('/{prestasi}/verifikasi', [PrestasiController::class, 'verify'])->middleware('permission:Approve Prestasi|Reject Prestasi')->name('verify')->where('prestasi', '[0-9]+');
            Route::post('/{prestasi}/anggota', [PrestasiController::class, 'addAnggota'])->middleware('permission:Create Prestasi')->name('add-anggota')->where('prestasi', '[0-9]+');
            Route::post('/{prestasi}/dosen', [PrestasiController::class, 'addDosen'])->middleware('permission:Create Prestasi')->name('add-dosen')->where('prestasi', '[0-9]+');
            Route::put('/{prestasi}', [PrestasiController::class, 'update'])->middleware('permission:Edit Prestasi')->name('update')->where('prestasi', '[0-9]+');
            Route::delete('/{prestasi}', [PrestasiController::class, 'destroy'])->middleware('permission:Delete Prestasi')->name('destroy')->where('prestasi', '[0-9]+');
            Route::delete('/{prestasi}/anggota/{anggota}', [PrestasiController::class, 'deleteAnggota'])->middleware('permission:Delete Prestasi')->name('delete-anggota')->where(['prestasi' => '[0-9]+', 'anggota' => '[0-9]+']);
            Route::delete('/{prestasi}/dosen/{dosen}', [PrestasiController::class, 'deleteDosen'])->middleware('permission:Delete Prestasi')->name('delete-dosen')->where(['prestasi' => '[0-9]+', 'dosen' => '[0-9]+']);
            Route::post('/{prestasi}/dokumen', [DokumenPrestasiController::class, 'store'])->middleware('permission:Create Prestasi')->name('add-dokumen')->where('prestasi', '[0-9]+');
            Route::delete('/{prestasi}/dokumen/{dokumen}', [DokumenPrestasiController::class, 'destroy'])->middleware('permission:Delete Prestasi')->name('delete-dokumen')->where(['prestasi' => '[0-9]+', 'dokumen' => '[0-9]+']);
            Route::get('/cetak/transkrip', [CetakPrestasiController::class, 'cetakTranskrip'])->middleware('permission:View Prestasi')->name('cetak.transkrip');
            Route::get('/cetak/kartu/{nim}', [CetakPrestasiController::class, 'cetakKartu'])->name('cetak.kartu');
        });

        // ========================================================
        // TEMPLATE DOKUMEN ROUTES
        // ========================================================
        Route::prefix('template')->name('template.')->group(function () {
            Route::get('/', [TemplateController::class, 'index'])->middleware('permission:View Template Dokumen')->name('index');
            Route::post('/', [TemplateController::class, 'store'])->middleware('permission:Manage Template Dokumen')->name('store');
            Route::get('/{template}', [TemplateController::class, 'show'])->middleware('permission:View Template Dokumen')->name('show')->where('template', '[0-9]+');
            Route::put('/{template}', [TemplateController::class, 'update'])->middleware('permission:Manage Template Dokumen')->name('update')->where('template', '[0-9]+');
            Route::delete('/{template}', [TemplateController::class, 'destroy'])->middleware('permission:Manage Template Dokumen')->name('destroy')->where('template', '[0-9]+');
            Route::get('/{template}/download', [TemplateController::class, 'download'])->middleware('permission:View Template Dokumen')->name('download')->where('template', '[0-9]+');
        });

        // ========================================================
        // INFORMASI KEGIATAN ROUTES (Admin routes)
        // ========================================================
        Route::prefix('informasi')->name('informasi.')->middleware('role:Super Admin,Kemahasiswaan,Ormawa')->group(function () {
            Route::post('/', [InformasiController::class, 'store'])->name('store');
            Route::put('/{informasi}', [InformasiController::class, 'update'])->name('update')->where('informasi', '[0-9]+');
            Route::delete('/{informasi}', [InformasiController::class, 'destroy'])->name('destroy')->where('informasi', '[0-9]+');
        });

        // ========================================================
        // USER MANAGEMENT ROUTES (Super Admin + Kemahasiswaan only)
        // ========================================================
        Route::prefix('users')->name('users.')->middleware('permission:View Users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->middleware('permission:Create Users')->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show')->where('user', '[0-9]+');
            Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:Edit Users')->name('update')->where('user', '[0-9]+');
            Route::delete('/{user}', [UserController::class, 'destroy'])->middleware('permission:Delete Users')->name('destroy')->where('user', '[0-9]+');
            Route::patch('/{user}/toggle-akses', [UserController::class, 'toggleAccess'])->middleware('permission:Edit Users')->name('toggle-access')->where('user', '[0-9]+');
            Route::patch('/{user}/assign-role', [UserController::class, 'assignRole'])->middleware('permission:Edit Users')->name('assign-role')->where('user', '[0-9]+');

            // Spatie Role & Permissions Management
            Route::get('/spatie/roles', [\App\Http\Controllers\Api\RolePermissionController::class, 'getRoles'])->name('spatie.roles');
            Route::get('/spatie/permissions', [\App\Http\Controllers\Api\RolePermissionController::class, 'getPermissions'])->name('spatie.permissions');
            Route::get('/{user}/permissions', [\App\Http\Controllers\Api\RolePermissionController::class, 'getUserPermissions'])->name('permissions.show')->where('user', '[0-9]+');
            Route::patch('/{user}/permissions', [\App\Http\Controllers\Api\RolePermissionController::class, 'syncUserPermissions'])->middleware('permission:Edit Users')->name('permissions.sync')->where('user', '[0-9]+');
        });

        // ========================================================
        // MONITORING ROUTES (Super Admin + DPMBEM + Kemahasiswaan only)
        // ========================================================
        Route::prefix('monitoring')->name('monitoring.')->middleware('permission:View Reports')->group(function () {
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
            'message' => 'Endpoint tidak ditemukan',
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
