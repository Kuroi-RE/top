<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @group User Management
 * Endpoints untuk pengelolaan pengguna (Admin only)
 */
class UserController
{
    /**
     * Daftar semua pengguna (Admin only)
     * 
     * Menampilkan daftar seluruh pengguna dalam sistem.
     *
     * @queryParam role string Filter berdasarkan role
     * @queryParam is_active boolean Filter berdasarkan status aktif
     * @queryParam per_page integer Jumlah data per halaman (default: 15)
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Daftar pengguna",
     *   "data": [...]
     * }
     * @response 403 {
     *   "status": "error",
     *   "message": "Unauthorized"
     * }
     */
    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $users = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar pengguna',
            'data' => UserResource::collection($users->items()),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
            ],
        ], 200);
    }

    /**
     * Tambah pengguna baru (Admin only)
     * 
     * Membuat akun pengguna baru dengan role tertentu.
     *
     * @bodyParam username string required Username (unik, max 50)
     * @bodyParam email string required Email (unik)
     * @bodyParam password string required Password (minimal 8 karakter)
     * @bodyParam password_confirmation string required Konfirmasi password
     * @bodyParam role string required Role (Ormawa, Mahasiswa, Kemahasiswaan, DPMBEM)
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "Pengguna berhasil dibuat",
     *   "data": {...}
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Validation failed",
     *   "errors": {...}
     * }
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil dibuat',
            'data' => new UserResource($user),
        ], 201);
    }

    /**
     * Detail pengguna (Admin only)
     * 
     * Melihat detail pengguna tertentu.
     *
     * @urlParam id integer required ID User
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Detail pengguna",
     *   "data": {...}
     * }
     */
    public function show(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail pengguna',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * Update pengguna (Admin only)
     * 
     * Mengedit data pengguna.
     *
     * @urlParam id integer required ID User
     * @bodyParam username string optional Username baru (unik)
     * @bodyParam email string optional Email baru
     * @bodyParam password string optional Password baru (minimal 8 karakter)
     * @bodyParam password_confirmation string optional Konfirmasi password baru
     * @bodyParam role string optional Role baru
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Pengguna berhasil diperbarui",
     *   "data": {...}
     * }
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil diperbarui',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * Hapus pengguna (Admin only)
     * 
     * Menghapus akun pengguna dari sistem.
     *
     * @urlParam id integer required ID User
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Pengguna berhasil dihapus"
     * }
     */
    public function destroy(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengguna berhasil dihapus',
        ], 200);
    }

    /**
     * Toggle akses pengguna (Admin only)
     * 
     * Mengaktifkan atau menonaktifkan akses pengguna.
     *
     * @urlParam id integer required ID User
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Status akses pengguna berhasil diubah",
     *   "data": {...}
     * }
     */
    public function toggleAccess(Request $request, User $user): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status akses pengguna berhasil diubah',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * Assign role kepada pengguna (Super Admin / Kemahasiswaan only)
     * 
     * Mengubah role pengguna. Super Admin dapat assign semua role,
     * sedangkan Kemahasiswaan hanya dapat assign Ormawa, Mahasiswa, dan DPMBEM.
     *
     * @urlParam id integer required ID User
     * @bodyParam role string required Role baru (Super Admin, Kemahasiswaan, DPMBEM, Ormawa, Mahasiswa)
     * @bodyParam ormawa_type string optional Tipe Ormawa (institusi/prodi) - required jika role Ormawa
     * @bodyParam ormawa_name string optional Nama Ormawa (UKM/Himpunan) - required jika role Ormawa
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Role pengguna berhasil diubah",
     *   "data": {...}
     * }
     * @response 403 {
     *   "status": "error",
     *   "message": "Unauthorized"
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Validation failed",
     *   "errors": {...}
     * }
     */
    public function assignRole(AssignRoleRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        // Clear ormawa fields if not assigning Ormawa role
        if ($data['role'] !== 'Ormawa') {
            $data['ormawa_type'] = null;
            $data['ormawa_name'] = null;
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Role pengguna berhasil diubah',
            'data' => new UserResource($user),
        ], 200);
    }
}
