<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 * Endpoints untuk autentikasi dan manajemen token API
 */
class AuthController
{
    /**
     * Login pengguna
     * 
     * Melakukan autentikasi pengguna berdasarkan username dan password.
     * Mengembalikan token yang dapat digunakan untuk API calls berikutnya.
     *
     * @bodyParam username string required Username pengguna
     * @bodyParam password string required Password pengguna
     * 
     * @response 200 {
     *   "status": "success",
     *   "message": "Login berhasil",
     *   "data": {
     *     "user": {...},
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
     *   }
     * }
     * @response 401 {
     *   "status": "error",
     *   "message": "Username atau password salah",
     *   "errors": {"credentials": "Invalid credentials"}
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => 'Username atau password salah',
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'account' => 'Akun Anda telah dinonaktifkan',
            ]);
        }

        $token = $user->createToken('api-token', ['*'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'user' => new UserResource($user),
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * Register pengguna baru
     * 
     * Mendaftarkan pengguna baru dengan default role Mahasiswa.
     * Pengguna dapat langsung login setelah register.
     * Role dapat diubah oleh Super Admin atau Kemahasiswaan.
     *
     * @bodyParam nim string required NIM pengguna (unique, max 20)
     * @bodyParam nama_depan string required Nama depan
     * @bodyParam nama_belakang string required Nama belakang
     * @bodyParam prodi string required Program studi
     * @bodyParam email string required Email pengguna (unique)
     * @bodyParam password string required Password minimal 8 karakter
     * @bodyParam password_confirmation string required Konfirmasi password
     * 
     * @response 201 {
     *   "status": "success",
     *   "message": "Registrasi berhasil",
     *   "data": {
     *     "user": {...},
     *     "token": "1|eyJ0eXAiOiJKV1QiLCJhbGc..."
     *   }
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Validation failed",
     *   "errors": {"email": "Email sudah terdaftar"}
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Generate username from email (remove domain)
            $emailParts = explode('@', $request->email);
            $baseUsername = $emailParts[0];
            
            // Ensure username is unique
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            \Log::info('Register request received', [
                'nim' => $request->input('nim'),
                'email' => $request->input('email'),
                'generated_username' => $username,
                'all_data' => $request->all(),
            ]);

            $user = User::create([
                'username' => $username,
                'nim' => $request->nim,
                'nama_depan' => $request->nama_depan,
                'nama_belakang' => $request->nama_belakang,
                'prodi' => $request->prodi,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Mahasiswa',
                'is_active' => true,
            ]);

            \Log::info('User created successfully', ['user_id' => $user->id_user, 'username' => $username]);

            $token = $user->createToken('api-token', ['*'])->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Register error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Registrasi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout pengguna
     * 
     * Mencabut token yang sedang digunakan pengguna.
     * Token tidak dapat digunakan lagi setelah logout.
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Logout berhasil"
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil',
        ], 200);
    }

    /**
     * Generate API Key untuk Admin
     * 
     * Membuat API Key baru yang dapat digunakan untuk akses programatik.
     * Hanya Admin (Kemahasiswaan) yang dapat mengakses endpoint ini.
     *
     * @response 201 {
     *   "status": "success",
     *   "message": "API Key berhasil dibuat",
     *   "data": {
     *     "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
     *   }
     * }
     * @response 403 {
     *   "status": "error",
     *   "message": "Unauthorized - Insufficient permissions"
     * }
     */
    public function generateToken(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized - Hanya Admin yang dapat membuat API Key',
            ], 403);
        }

        $token = $request->user()->createToken('api-key', ['*'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'API Key berhasil dibuat',
            'data' => [
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Dapatkan informasi pengguna saat ini
     * 
     * Mengembalikan data pengguna yang sedang login.
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Data pengguna",
     *   "data": {...}
     * }
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna',
            'data' => new UserResource($request->user()),
        ], 200);
    }
}
