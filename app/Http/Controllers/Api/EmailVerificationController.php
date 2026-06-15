<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SendVerificationEmail;
use App\Models\EmailVerificationToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

/**
 * @group Email Verification
 * Endpoints untuk verifikasi email pengguna
 */
class EmailVerificationController
{
    /**
     * Verifikasi email pengguna
     *
     * Memvalidasi token verifikasi dan mengaktifkan akun pengguna.
     *
     * @bodyParam token string required Token verifikasi yang dikirim ke email
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Email berhasil diverifikasi."
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Token tidak valid atau sudah kadaluarsa."
     * }
     */
    public function verify(Request $request): JsonResponse
    {
        $tokenRecord = EmailVerificationToken::where('token', $request->token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$tokenRecord) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token tidak valid atau sudah kadaluarsa.',
            ], 422);
        }

        // Activate the user account
        $tokenRecord->user->update([
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        // Mark the token as used
        $tokenRecord->update(['used_at' => now()]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Email berhasil diverifikasi.',
        ], 200);
    }

    /**
     * Kirim ulang email verifikasi
     *
     * Mengirim ulang email verifikasi ke alamat email yang diberikan.
     * Dibatasi maksimal 3 permintaan per 60 menit.
     *
     * @bodyParam email string required Alamat email yang terdaftar
     *
     * @response 200 {
     *   "status": "success",
     *   "message": "Email verifikasi telah dikirim ulang."
     * }
     * @response 422 {
     *   "status": "error",
     *   "message": "Email sudah diverifikasi."
     * }
     * @response 429 {
     *   "status": "error",
     *   "message": "Terlalu banyak permintaan. Coba lagi dalam 60 menit."
     * }
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email tidak ditemukan.',
            ], 422);
        }

        if ($user->is_active) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email sudah diverifikasi.',
            ], 422);
        }

        $executed = RateLimiter::attempt(
            'resend-verification:' . $user->id_user,
            3,
            fn () => true,
            3600
        );

        if (!$executed) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terlalu banyak permintaan. Coba lagi dalam 60 menit.',
            ], 429);
        }

        // Invalidate all existing unused tokens for this user
        EmailVerificationToken::where('user_id', $user->id_user)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Dispatch job to generate a new token and send the email
        SendVerificationEmail::dispatch($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'Email verifikasi telah dikirim ulang.',
        ], 200);
    }
}
