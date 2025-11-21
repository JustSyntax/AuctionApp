<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\HistoryLelang;
use App\Models\Lelang;
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
        $role = session('role');
        $userId = session('user_id');
        
        $data = [];

        // === LOGIC UNTUK ADMINISTRATOR ===
        if ($role == 'administrator') {
            // 1. Statistik Umum
            $data['total_petugas'] = Petugas::count();
            $data['total_masyarakat'] = Masyarakat::count();
            $data['total_barang'] = Barang::count();
            
            // 2. Total Pendapatan Keseluruhan
            $data['total_pendapatan'] = Lelang::where('status', 'ditutup')->sum('harga_akhir');

            // 3. LEADERBOARD PETUGAS (Siapa yg paling rajin nutup lelang)
            // Kita hitung jumlah lelang status 'ditutup' per petugas
            $data['top_petugas'] = Petugas::withCount(['lelang' => function ($query) {
                    $query->where('status', 'ditutup');
                }])
                ->whereHas('level', function($q) {
                    $q->where('level', 'petugas'); // Hanya ambil user level petugas
                })
                ->orderBy('lelang_count', 'desc') // Urutkan dari yg terbanyak
                ->limit(5) // Ambil top 5
                ->get();
        } 
        
        // === LOGIC UNTUK PETUGAS BIASA ===
        else {
            // 1. Statistik Pribadi
            $data['lelang_dibuka'] = Lelang::where('id_petugas', $userId)->where('status', 'dibuka')->count();
            $data['lelang_ditutup'] = Lelang::where('id_petugas', $userId)->where('status', 'ditutup')->count();
            
            // 2. Pendapatan yang dihasilkan Petugas ini
            $data['pendapatan_saya'] = Lelang::where('id_petugas', $userId)
                                             ->where('status', 'ditutup')
                                             ->sum('harga_akhir');
        }

        return view('petugas.dashboard', compact('data', 'role'));
    }

    public function dashboardMasyarakat()
    {
        $userId = session('user_id');
        $data = [];

        // 1. Total Partisipasi (Berapa kali nge-bid)
        $data['total_bids'] = HistoryLelang::where('id_user', $userId)->count();

        // 2. Barang Diikuti (Jumlah barang unik yang pernah ditawar)
        $data['items_joined'] = HistoryLelang::where('id_user', $userId)->distinct('id_lelang')->count('id_lelang');

        // 3. Lelang Dimenangkan
        // Ambil semua lelang ditutup, lalu filter di PHP (karena relasi pemenang agak kompleks di query builder murni)
        $lelangTutup = Lelang::with('pemenang')->where('status', 'ditutup')->get();
        $data['won_count'] = $lelangTutup->filter(function($lelang) use ($userId) {
            return $lelang->pemenang && $lelang->pemenang->id_user == $userId;
        })->count();

        // 4. Total Pengeluaran (Total harga barang yang dimenangkan)
        $data['total_spent'] = $lelangTutup->filter(function($lelang) use ($userId) {
            return $lelang->pemenang && $lelang->pemenang->id_user == $userId;
        })->sum('harga_akhir');

        return view('masyarakat.dashboard', compact('data'));
    }
}