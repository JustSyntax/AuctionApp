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

        // Login PETUGAS
        $petugas = Petugas::where('username', $request->username)->first();
        if ($petugas && Hash::check($request->password, $petugas->password)) {
            // Simpan session manual
            session([
                'role' => $petugas->level->level, // administrator / petugas
                'user_id' => $petugas->id_petugas,
                'name' => $petugas->nama_petugas
            ]);
            return redirect()->route('dashboard.petugas');
        }

        // Login MASYARAKAT
        $mas = Masyarakat::where('username', $request->username)
                        ->where('status', 'aktif')
                        ->first();
        if ($mas && Hash::check($request->password, $mas->password)) {
            session([
                'role' => 'masyarakat',
                'user_id' => $mas->id,
                'name' => $mas->nama_lengkap
            ]);
            return redirect()->route('dashboard.masyarakat');
        }

        return back()->with('error', 'Username / NIK atau Password salah');
    }


    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:tb_masyarakat,nik',
            'nama_lengkap' => 'required',
            'username' => 'required|unique:tb_masyarakat,username',
            'password' => 'required|min:5|confirmed',
            'telp' => 'required'
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
        return view('petugas.dashboard'); // nanti blade masih kosong
    }

    public function dashboardMasyarakat()
    {
        return view('masyarakat.dashboard'); // nanti blade masih kosong
    }
}
