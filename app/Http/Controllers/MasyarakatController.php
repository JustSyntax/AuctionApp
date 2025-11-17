<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masyarakat;
use App\Models\Petugas; // PENTING: Import Model Petugas buat cek username
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MasyarakatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Query data
        $masyarakats = Masyarakat::query()
            ->when($search, function($query, $search) {
                return $query->where('nama_lengkap', 'like', "%{$search}%")
                             ->orWhere('nik', 'like', "%{$search}%")
                             ->orWhere('username', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('masyarakat.index', compact('masyarakats'));
    }

    public function create()
    {
        return view('masyarakat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|numeric|unique:tb_masyarakat,nik',
            'nama_lengkap' => 'required|string|max:25',
            'telp' => 'required|numeric|digits_between:10,13',
            'alamat' => 'required|string',
            
            // Validasi Username Lintas Tabel (Masyarakat & Petugas)
            'username' => [
                'required', 'string', 'max:25', 'alpha_dash',
                function ($attribute, $value, $fail) {
                    if (Masyarakat::where('username', $value)->exists()) {
                        $fail('Username sudah digunakan oleh Masyarakat lain.');
                    }
                    if (Petugas::where('username', $value)->exists()) {
                        $fail('Username sudah digunakan oleh Petugas/Admin.');
                    }
                },
            ],

            // Validasi Password + Konfirmasi
            'password' => 'required|min:8|confirmed', 
        ], [
            'nik.digits' => 'NIK harus berjumlah 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, strip, dan underscore.',
        ]);

        Masyarakat::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'status' => 'aktif',
        ]);

        return redirect()->route('masyarakat.index')->with('success', 'Data masyarakat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $masyarakat = Masyarakat::findOrFail($id);
        return view('masyarakat.edit', compact('masyarakat'));
    }

    public function update(Request $request, $id)
    {
        $masyarakat = Masyarakat::findOrFail($id);

        $request->validate([
            'nik' => ['required', 'digits:16', 'numeric', Rule::unique('tb_masyarakat')->ignore($masyarakat->id_user, 'id_user')],
            'nama_lengkap' => 'required|string|max:25',
            'telp' => 'required|numeric|digits_between:10,13',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,diblokir',

            // Validasi Username Lintas Tabel (Ignore diri sendiri)
            'username' => [
                'required', 'string', 'max:25', 'alpha_dash',
                function ($attribute, $value, $fail) use ($masyarakat) {
                    // Cek di tabel masyarakat (kecuali punya dia sendiri)
                    if (Masyarakat::where('username', $value)->where('id_user', '!=', $masyarakat->id_user)->exists()) {
                        $fail('Username sudah digunakan.');
                    }
                    // Cek di tabel petugas (MUTLAK gak boleh sama)
                    if (Petugas::where('username', $value)->exists()) {
                        $fail('Username sudah digunakan oleh Petugas/Admin.');
                    }
                },
            ],

            // Validasi Password (Opsional, tapi kalau diisi harus valid)
            'password' => ['nullable', 'min:8', 'confirmed', 
                function ($attribute, $value, $fail) use ($masyarakat) {
                    // Cek apakah password baru SAMA dengan yang lama
                    if ($value && Hash::check($value, $masyarakat->password)) {
                        $fail('Password baru tidak boleh sama dengan password lama.');
                    }
                }
            ],
        ], [
            'nik.digits' => 'NIK harus berjumlah 16 digit.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $data = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $masyarakat->update($data);

        return redirect()->route('masyarakat.index')->with('success', 'Data masyarakat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $masyarakat = Masyarakat::findOrFail($id);
        $masyarakat->delete();
        return redirect()->route('masyarakat.index')->with('success', 'Data masyarakat berhasil dihapus.');
    }

    // --- FITUR BARU: TOGGLE STATUS (KLIK LANGSUNG GANTI) ---
    public function toggleStatus($id)
    {
        $masyarakat = Masyarakat::findOrFail($id);
        
        // Tukar status: kalau aktif jadi blokir, kalau blokir jadi aktif
        $newStatus = ($masyarakat->status == 'aktif') ? 'diblokir' : 'aktif';
        
        $masyarakat->update(['status' => $newStatus]);

        $pesan = ($newStatus == 'aktif') ? 'User berhasil diaktifkan kembali.' : 'User berhasil diblokir.';
        return back()->with('success', $pesan);
    }
}