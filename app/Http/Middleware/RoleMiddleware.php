<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek Login via Session
        if (!session()->has('role')) {
            return redirect()->route('login');
        }

        // Cek Kesesuaian Role
        if (session('role') !== $role) {
            abort(403, 'Forbidden: Role tidak sesuai.');
        }

        return $next($request);
    }
}