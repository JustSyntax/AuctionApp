<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petugas;
use App\Models\Masyarakat; // Buat cek username ganda
use App\Models\Level;      // Buat dropdown level
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    // Middleware manual buat mastiin cuma 'administrator' yang bisa akses
    private function checkAdmin()
    {
        if (session('role') !== 'administrator') {
            abort(403, 'AKSES DITOLAK. HANYA ADMINISTRATOR.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin(); // Cek Security

        $search = $request->input('search');

        // Eager load 'level' biar query ringan
        $petugas = Petugas::with('level')
            ->when($search, function($query, $search) {
                return $query->where('nama_petugas', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%");
            })
            ->orderBy('id_level', 'asc') // Urutkan Admin dulu
            ->get();

        return view('petugas.index', compact('petugas'));
    }

    public function create()
    {
        $this->checkAdmin();
        $levels = Level::all(); // Ambil data level buat dropdown
        return view('petugas.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'nama_petugas' => 'required|string|max:25',
            'id_level'     => 'required|exists:tb_level,id_level',
            'password'     => 'required|min:8|confirmed',
            
            // Validasi Username Lintas Tabel
            'username' => [
                'required', 'string', 'max:25', 'alpha_dash',
                function ($attribute, $value, $fail) {
                    if (Petugas::where('username', $value)->exists()) $fail('Username sudah dipakai Petugas lain.');
                    if (Masyarakat::where('username', $value)->exists()) $fail('Username sudah dipakai Masyarakat.');
                },
            ],
        ], [
            'id_level.required' => 'Level petugas wajib dipilih.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Petugas::create([
            'nama_petugas' => $request->nama_petugas,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'id_level'     => $request->id_level,
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->checkAdmin();
        $petugas = Petugas::findOrFail($id);
        $levels  = Level::all();
        return view('petugas.edit', compact('petugas', 'levels'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $petugas = Petugas::findOrFail($id);

        $request->validate([
            'nama_petugas' => 'required|string|max:25',
            'id_level'     => 'required|exists:tb_level,id_level',
            'password'     => 'nullable|min:8|confirmed', // Opsional

            'username' => [
                'required', 'string', 'max:25', 'alpha_dash',
                function ($attribute, $value, $fail) use ($petugas) {
                    // Cek tabel petugas (kecuali diri sendiri)
                    if (Petugas::where('username', $value)->where('id_petugas', '!=', $petugas->id_petugas)->exists()) {
                        $fail('Username sudah dipakai.');
                    }
                    // Cek tabel masyarakat
                    if (Masyarakat::where('username', $value)->exists()) {
                        $fail('Username sudah dipakai Masyarakat.');
                    }
                },
            ],
        ]);

        $data = [
            'nama_petugas' => $request->nama_petugas,
            'username'     => $request->username,
            'id_level'     => $request->id_level,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $petugas->update($data);

        return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        
        // Cegah Admin menghapus dirinya sendiri yang sedang login
        if ($id == session('user_id')) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun sendiri saat sedang login.']);
        }

        $petugas = Petugas::findOrFail($id);
        $petugas->delete();
        
        return redirect()->route('petugas.index')->with('success', 'Data petugas berhasil dihapus.');
    }
}