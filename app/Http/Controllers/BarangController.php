<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // --- BAGIAN 1: INDEX, CREATE, STORE ---

    public function index(Request $request) // Tambahin Request $request
    {
        // Ambil inputan pencarian
        $search = $request->input('search');

        // Query Data
        $barangs = Barang::query()
            ->when($search, function($query, $search) {
                return $query->where('nama_barang', 'like', "%{$search}%")
                             ->orWhere('deskripsi_barang', 'like', "%{$search}%");
            })
            ->orderBy('tgl', 'desc')
            ->get();

        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        // Validasi
        $validatedData = $request->validate([
            'nama_barang'      => 'required|string|max:25',
            'tgl'              => 'required|date',
            'harga_awal'       => 'required|integer|min:0',
            'deskripsi_barang' => 'required|string|max:100',
            'gambar'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Upload Gambar
        if ($request->hasFile('gambar')) {
            $fileName = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $gambarPath = $request->file('gambar')->storeAs('barang', $fileName, 'public');
            $validatedData['gambar'] = $gambarPath;
        }

        // Simpan
        Barang::create($validatedData);

        return redirect()->route('barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }
    // --- BAGIAN 2: EDIT, UPDATE, DESTROY ---

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        // Validasi
        $validatedData = $request->validate([
            'nama_barang'      => 'required|string|max:25',
            'tgl'              => 'required|date',
            'harga_awal'       => 'required|integer|min:0',
            'deskripsi_barang' => 'required|string|max:100',
            'gambar'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Logic Update Gambar
        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama jika ada
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            // 2. Upload gambar baru
            $fileName = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $gambarPath = $request->file('gambar')->storeAs('barang', $fileName, 'public');
            $validatedData['gambar'] = $gambarPath;
        }

        // Update Data
        $barang->update($validatedData);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        // Hapus gambar fisik jika ada
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }
        
        // Hapus data di DB
        $barang->delete();
        
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}