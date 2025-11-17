<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminPetugas
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah session 'role' ada (artinya sudah login)
        if (!session()->has('role')) {
            return redirect()->route('login');
        }

        // Ambil role
        $userRole = session('role');

        // Cek apakah Admin ATAU Petugas
        if ($userRole == 'administrator' || $userRole == 'petugas') {
            return $next($request);
        }

        // Kalau bukan keduanya (misal masyarakat), tolak.
        abort(403, 'AKSES DITOLAK. HANYA UNTUK ADMIN & PETUGAS.');
    }
}