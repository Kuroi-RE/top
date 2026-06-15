<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ApiService
{
    /**
     * Get a pre-configured HTTP client pointing to the API base URL
     * with the authenticated user's Sanctum token.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public static function getClient()
    {
        $baseUrl = env('API_BASE_URL', url('/api/v1'));
        $user = Auth::user();
        
        if (!$user) {
            return Http::baseUrl($baseUrl);
        }

        // Retrieve existing token from session, or create a new one
        $token = session('api_token');
        $tokenValid = false;
        if ($token) {
            $tokenParts = explode('|', $token);
            $tokenId = count($tokenParts) > 1 ? $tokenParts[0] : $token;
            if (is_numeric($tokenId)) {
                $tokenValid = $user->tokens()->where('id', $tokenId)->exists();
            }
        }

        if (!$token || !$tokenValid) {
            // Delete any existing tokens named 'web-session-token' to prevent clutter
            $user->tokens()->where('name', 'web-session-token')->delete();
            $token = $user->createToken('web-session-token')->plainTextToken;
            session(['api_token' => $token]);
        }

        return Http::withToken($token)->acceptJson()->baseUrl($baseUrl);
    }
}
