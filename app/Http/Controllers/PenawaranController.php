<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lelang;
use App\Models\HistoryLelang;
use Illuminate\Support\Facades\Auth;

class PenawaranController extends Controller
{
    // Tampilkan daftar lelang yang sedang DIBUKA
    public function index(Request $request) // Tambah Request
    {
        $search = $request->input('search');

        $lelangs = Lelang::with(['barang', 'pemenang.masyarakat'])
            ->where('status', 'dibuka')
            // FILTER SEARCH
            ->when($search, function($query, $search) {
                return $query->whereHas('barang', function($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('masyarakat.lelang.index', compact('lelangs'));
    }

    // Tampilkan detail barang & form bid
    public function show($id)
    {
        $lelang = Lelang::with(['barang', 'history.masyarakat', 'pemenang'])
            ->where('status', 'dibuka')
            ->findOrFail($id);

        return view('masyarakat.lelang.show', compact('lelang'));
    }

    // Logic NGE-BID
    public function store(Request $request, $id)
    {
        $request->validate([
            'penawaran_harga' => 'required|numeric'
        ]);

        $lelang = Lelang::findOrFail($id);
        $userId = session('user_id');

        // 1. Tentukan harga minimal (Harga Akhir saat ini ATAU Harga Awal barang)
        $harga_tertinggi_saat_ini = $lelang->harga_akhir > 0 ? $lelang->harga_akhir : $lelang->barang->harga_awal;

        // VALIDASI: Harga tawaran harus LEBIH BESAR dari harga saat ini
        if ($request->penawaran_harga <= $harga_tertinggi_saat_ini) {
            return back()->withErrors(['penawaran_harga' => 'Tawaran harus lebih tinggi dari Rp ' . number_format($harga_tertinggi_saat_ini)]);
        }

        // VALIDASI: Cek apakah user ini ADALAH pemenang tertinggi saat ini? (Gaboleh outbid diri sendiri)
        $pemenang_saat_ini = $lelang->pemenang; // Ambil relasi hasOne pemenang
        if ($pemenang_saat_ini && $pemenang_saat_ini->id_user == $userId) {
            return back()->withErrors(['error' => 'Anda saat ini sudah memimpin penawaran tertinggi!']);
        }

        // PROSES SIMPAN BID
        // 1. Masuk ke History
        HistoryLelang::create([
            'id_lelang' => $id,
            'id_barang' => $lelang->id_barang,
            'id_user'   => $userId,
            'penawaran_harga' => $request->penawaran_harga
        ]);

        // 2. Update Harga Akhir di tabel Lelang biar realtime
        $lelang->update([
            'harga_akhir' => $request->penawaran_harga
        ]);

        return back()->with('success', 'Penawaran berhasil! Anda memimpin saat ini.');
    }
}