<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @group Role & Permission Management
 * Endpoints untuk manajemen Spatie Roles dan Permissions (Admin/Kemahasiswaan only)
 */
class RolePermissionController
{
    /**
     * Daftar Roles
     * 
     * Menampilkan semua role yang tersedia di Spatie.
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar Roles",
     *   "data": [...]
     * }
     */
    public function getRoles(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Daftar Roles',
            'data' => Role::select('id', 'name')->get()
        ], 200);
    }

    /**
     * Daftar Permissions
     * 
     * Menampilkan semua hak akses (permissions) yang tersedia dalam sistem.
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar Permissions",
     *   "data": [...]
     * }
     */
    public function getPermissions(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Daftar Permissions',
            'data' => Permission::select('id', 'name')->get()
        ], 200);
    }

    /**
     * Lihat Permissions User
     * 
     * Menampilkan daftar permissions yang dimiliki secara langsung (direct permissions) oleh user tertentu.
     *
     * @urlParam user integer required ID User
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Permissions User",
     *   "data": ["tambah dosen", "verifikasi proposal"]
     * }
     */
    public function getUserPermissions(User $user): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Permissions User',
            'data' => $user->getAllPermissions()->pluck('name')
        ], 200);
    }

    /**
     * Sync User Permissions
     * 
     * Menyinkronkan (mengganti secara keseluruhan) hak akses tambahan spesifik pada user tertentu.
     * Ini berguna jika user butuh akses khusus di luar akses bawaan role-nya.
     *
     * @urlParam user integer required ID User
     * @bodyParam permissions array required Array nama permissions. Contoh: ["verifikasi proposal"]
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Permissions berhasil diperbarui",
     *   "data": [...]
     * }
     */
    public function syncUserPermissions(Request $request, User $user): JsonResponse
    {
        // Build the flat list of all known permission names from config
        $knownPermissions = collect(config('permissions.role_defaults', []))
            ->flatten()
            ->unique()
            ->values()
            ->all();

        $inRule = 'in:' . implode(',', $knownPermissions);

        $request->validate([
            'permissions' => 'present|array',
            'permissions.*' => ['string', $inRule],
        ], [
            'permissions.present' => 'Parameter permissions wajib disertakan (boleh array kosong)',
            'permissions.array' => 'Permissions harus berupa array',
            'permissions.*.in' => 'Salah satu permission tidak dikenali atau tidak termasuk dalam daftar permission yang valid',
        ]);

        // Menyinkronkan direct permissions.
        // Jika array kosong diberikan, semua direct permission akan dicabut.
        $user->syncPermissions($request->permissions);

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions berhasil diperbarui',
            'data' => $user->permissions->pluck('name')
        ], 200);
    }
}
