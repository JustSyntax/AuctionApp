<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\Masyarakat;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('landing');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // 1. CEK PETUGAS
        $petugas = Petugas::where('username', $request->username)->first();

        if ($petugas) {
            if (Hash::check($request->password, $petugas->password)) {
                session([
                    'role' => $petugas->level->level, // Pastikan relasi level aman
                    'user_id' => $petugas->id_petugas,
                    'name' => $petugas->nama_petugas
                ]);
                return redirect()->route('dashboard.petugas');
            } else {
                return back()->with('login_error', 'password');
            }
        }

        // 2. CEK MASYARAKAT
        // Hapus where('status', 'aktif') disini agar kita bisa deteksi user diblokir
        $mas = Masyarakat::where(function($query) use ($request) {
                            $query->where('username', $request->username)
                                  ->orWhere('nik', $request->username);
                        })
                        ->first();
        
        if ($mas) {
            // Cek Password Dulu
            if (Hash::check($request->password, $mas->password)) {
                
                // BARU CEK STATUS DISINI
                if ($mas->status == 'diblokir') {
                    return back()->with('login_error', 'blocked');
                }

                // Kalau Aman (Aktif)
                session([
                    'role' => 'masyarakat',
                    'user_id' => $mas->id_user, // Sesuaikan primary key (id_user/id)
                    'name' => $mas->nama_lengkap
                ]);
                return redirect()->route('dashboard.masyarakat');
            } else {
                return back()->with('login_error', 'password');
            }
        }

        // 3. GAGAL KEDUANYA
        return back()->with('login_error', 'username');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|numeric|unique:tb_masyarakat,nik',
            'nama_lengkap' => 'required',
            'telp' => 'required',
            'password' => 'required|min:8|confirmed',
            
            // VALIDASI USERNAME GANDA (Cross Check 2 Tabel)
            'username' => [
                'required', 'string', 'max:25', 'alpha_dash',
                function ($attribute, $value, $fail) {
                    if (Masyarakat::where('username', $value)->exists()) {
                        $fail('Username sudah digunakan.');
                    }
                    if (Petugas::where('username', $value)->exists()) {
                        $fail('Username sudah digunakan oleh Petugas.');
                    }
                },
            ],
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar. Silakan login.',
            'nik.digits' => 'NIK harus 16 digit.',
            'username.alpha_dash' => 'Username tidak boleh pakai spasi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        Masyarakat::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'telp' => $request->telp,
            'status' => 'aktif'
        ]);

        return back()->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login');
    }

    // DASHBOARD VIEWS
    public function dashboardPetugas()
    {
        return view('petugas.dashboard');
    }

    public function dashboardMasyarakat()
    {
        return view('masyarakat.dashboard');
    }
}