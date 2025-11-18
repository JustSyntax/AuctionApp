<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('role')) {
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}