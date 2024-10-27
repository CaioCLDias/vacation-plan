<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('oauth/token')) {
            return $next($request);
        }

        if (!Auth::guard('api')->check()) {
            return response()->json(['error' => 'Token is missing or invalid.'], 401);
        }

        return $next($request);
    }
}
