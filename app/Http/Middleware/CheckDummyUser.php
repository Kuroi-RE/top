<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDummyUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated via Laravel auth or session dummy_user
        if (!auth()->check() && !session('dummy_user')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
