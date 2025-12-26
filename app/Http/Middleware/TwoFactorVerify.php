<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorVerify
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->is_2fa_enabled) {
            
            // Allow access to the verification route and logout
            if ($request->routeIs('2fa.verify') || $request->routeIs('logout')) {
                return $next($request);
            }

            // Check if verified in session
            if (!$request->session()->has('2fa_verified')) {
                // Generate a code if one doesn't exist or is expired (Optional auto-send)
                // For now, just redirect, let the page handle sending
                return redirect()->route('2fa.verify');
            }
        }

        return $next($request);
    }
}
