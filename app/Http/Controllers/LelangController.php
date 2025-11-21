<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lelang;
use App\Models\Barang;
use App\Models\HistoryLelang;
use Illuminate\Support\Facades\Auth;

class LelangController extends Controller
{
    // Middleware Check (Manual)
    private function checkPetugas()
    {
        if (!session()->has('role') || (session('role') != 'petugas' && session('role') != 'administrator')) {
            abort(403, 'Akses Ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->checkPetugas();
        
        $search = $request->input('search');
        $userId = session('user_id');

        $lelangs = Lelang::with(['barang', 'pemenang.masyarakat']) 
            ->where('id_petugas', $userId) 
            ->when($search, function($query, $search) {
                return $query->whereHas('barang', function($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%");
                });
            })
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)->withQueryString(); // Pagination 10

        return view('petugas.lelang.index', compact('lelangs'));
    }

    public function create()
    {
        $this->checkPetugas();

        // REVISI: Tampilkan SEMUA barang.
        // Petugas boleh memilih barang yang sama lagi (duplicate allowed).
        $barangs = Barang::all(); 

        return view('petugas.lelang.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $this->checkPetugas();

        $request->validate([
            'id_barang' => 'required|exists:tb_barang,id_barang',
            'tgl_lelang' => 'required|date',
            'status' => 'required|in:dibuka,ditutup',
        ]);

        // REVISI: VALIDASI CEK DUPLIKAT DIHAPUS
        // Agar petugas bisa melelang barang yang sama lagi.

        Lelang::create([
            'id_barang'   => $request->id_barang,
            'tgl_lelang'  => $request->tgl_lelang,
            'harga_akhir' => 0,
            'id_petugas'  => session('user_id'),
            'status'      => $request->status,
        ]);

        return redirect()->route('lelang.index')->with('success', 'Lelang berhasil dibuka!');
    }

    public function show($id)
    {
        if (!session()->has('role')) abort(403);

        $userId = session('user_id');
        $role = session('role');

        $query = Lelang::with([
            'barang', 
            'petugas', 
            'history.masyarakat',
            'pemenang.masyarakat'
        ]);

        // REVISI: Kalau petugas biasa, cuma liat punya sendiri. Admin bebas.
        if ($role == 'petugas') {
            $query->where('id_petugas', $userId);
        }

        $lelang = $query->findOrFail($id);
            
        return view('petugas.lelang.show', compact('lelang'));
    }


    /**
     * UPDATE (UNTUK TOGGLE STATUS)
     */
    public function update(Request $request, $id)
    {
        $this->checkPetugas();
        $lelang = Lelang::where('id_lelang', $id)->where('id_petugas', session('user_id'))->firstOrFail();

        // Logic Toggle Status (Buka <-> Tutup)
        if ($request->has('status_toggle')) {
            $newStatus = ($lelang->status == 'dibuka') ? 'ditutup' : 'dibuka';
            
            // REVISI: Logic Harga
            // Kita cari harga tertinggi saat ini
            $pemenang = HistoryLelang::where('id_lelang', $id)
                                    ->orderBy('penawaran_harga', 'DESC')
                                    ->first();
            
            // Kalau ada penawar, pakai harga itu. Kalau belum ada, pake 0 (atau harga awal).
            // PENTING: Saat dibuka kembali, harga TIDAK DI-RESET ke 0, tapi tetep pakai harga terakhir.
            $harga_terakhir = $pemenang ? $pemenang->penawaran_harga : 0;

            $lelang->update([
                'status' => $newStatus,
                'harga_akhir' => $harga_terakhir
            ]);

            $pesan = ($newStatus == 'ditutup') ? 'Lelang DITUTUP.' : 'Lelang DIBUKA kembali.';
            return back()->with('success', $pesan);
        }
        
        return back();
    }

    public function destroy($id)
    {
        $this->checkPetugas();
        $lelang = Lelang::where('id_lelang', $id)->where('id_petugas', session('user_id'))->firstOrFail();
        
        // Validasi: Jangan hapus kalo udah ada history biar data aman
        $adaHistory = HistoryLelang::where('id_lelang', $id)->exists();
        
        if ($adaHistory) {
            return back()->withErrors(['error' => 'Gagal hapus! Lelang ini sudah memiliki riwayat penawaran.']);
        }
        
        $lelang->delete();
        return redirect()->route('lelang.index')->with('success', 'Data lelang dihapus.');
    }
}