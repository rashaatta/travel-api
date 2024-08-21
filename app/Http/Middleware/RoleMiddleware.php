<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            abort(401);
        }

        if (! auth()->user()->roles()->where('name', $role)->exists()) {
           abort(403);
           // return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}

