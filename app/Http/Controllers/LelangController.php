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
        // Pastikan user adalah petugas (atau admin juga boleh, tergantung kebijakan)
        if (!session()->has('role') || (session('role') != 'petugas' && session('role') != 'administrator')) {
            abort(403, 'Akses Ditolak.');
        }
    }

    public function index(Request $request)
    {
        $this->checkPetugas();
        
        $search = $request->input('search');
        $userId = session('user_id'); // Ambil ID petugas yang login

        // Query: Hanya ambil lelang milik petugas yang sedang login
        $lelangs = Lelang::with('barang')
            ->where('id_petugas', $userId) 
            ->when($search, function($query, $search) {
                // Cari berdasarkan nama barang lewat relasi
                return $query->whereHas('barang', function($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%");
                });
            })
            ->orderBy('status', 'asc') // Yang 'dibuka' di atas
            ->orderBy('created_at', 'desc')
            ->get();

        return view('petugas.lelang.index', compact('lelangs'));
    }

    public function create()
    {
        $this->checkPetugas();

        // Ambil barang yang BELUM pernah dilelang ATAU yang lelangnya sudah 'ditutup'
        $barangs = Barang::whereDoesntHave('lelang', function($q){
            $q->where('status', 'dibuka');
        })->get(); 
        // Note: Saya ubah logicnya biar lebih bener, 
        // barang yg 'dibuka' gak muncul lagi di list.

        return view('petugas.lelang.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $this->checkPetugas();

        $request->validate([
            'id_barang' => 'required|exists:tb_barang,id_barang',
            'tgl_lelang' => 'required|date',
            'status' => 'required|in:dibuka,ditutup', // <-- 1. VALIDASI TAMBAHAN
        ]);

        // Cek apakah barang ini sedang dilelang (status dibuka)
        // (Logic ini harusnya udah dicover query di function create(), tapi kita double check)
        $isExist = Lelang::where('id_barang', $request->id_barang)
                         ->where('status', 'dibuka')
                         ->exists();

        if($isExist) {
            return back()->withErrors(['error' => 'Barang ini sedang dalam proses lelang aktif.']);
        }

        Lelang::create([
            'id_barang'   => $request->id_barang,
            'tgl_lelang'  => $request->tgl_lelang,
            'harga_akhir' => 0,
            'id_petugas'  => session('user_id'),
            'status'      => $request->status, // <-- 2. DIUBAH (Ambil dari form)
        ]);

        return redirect()->route('lelang.index')->with('success', 'Lelang berhasil dibuka!');
    }

    public function show($id)
    {
        $this->checkPetugas();
        
        // Ambil ID Petugas yg login
        $userId = session('user_id');

        // Eager load semua relasi yg kita butuhkan
        $lelang = Lelang::with([
                'barang', 
                'petugas', 
                'history.masyarakat', // Ambil semua history + data masyarakatnya
                'pemenang.masyarakat' // Ambil 1 history tertinggi (pemenang) + data masyarakatnya
            ])
            ->where('id_petugas', $userId) // Pastikan ini lelang dia
            ->findOrFail($id);
            
        return view('petugas.lelang.show', compact('lelang'));
    }


    /**
     * UPDATE (UNTUK TUTUP LELANG)
     */
    public function update(Request $request, $id)
    {
        $this->checkPetugas();
        $lelang = Lelang::where('id_lelang', $id)->where('id_petugas', session('user_id'))->firstOrFail();

        // LOGIC BARU: TOGGLE STATUS (Buka <-> Tutup)
        if ($request->has('status_toggle')) {
            $newStatus = ($lelang->status == 'dibuka') ? 'ditutup' : 'dibuka';
            
            // Jika mau DITUTUP, set harga akhir
            if ($newStatus == 'ditutup') {
                $pemenang = \App\Models\HistoryLelang::where('id_lelang', $id)
                                        ->orderBy('penawaran_harga', 'DESC')
                                        ->first();
                $harga_akhir_final = $pemenang ? $pemenang->penawaran_harga : $lelang->barang->harga_awal;
                
                $lelang->update(['status' => 'ditutup', 'harga_akhir' => $harga_akhir_final]);
                return back()->with('success', 'Lelang DITUTUP. Pemenang ditetapkan.');
            } 
            // Jika mau DIBUKA KEMBALI
            else {
                $lelang->update(['status' => 'dibuka', 'harga_akhir' => 0]); // Reset harga akhir (opsional)
                return back()->with('success', 'Lelang DIBUKA kembali.');
            }
        }
        
        return back();
    }

    public function destroy($id)
    {
        $this->checkPetugas();
        $lelang = Lelang::where('id_lelang', $id)->where('id_petugas', session('user_id'))->firstOrFail();
        
        // CEGAH HAPUS JIKA SUDAH ADA PEMENANG (HISTORY)
        $adaHistory = \App\Models\HistoryLelang::where('id_lelang', $id)->exists();
        
        if ($adaHistory) {
            return back()->withErrors(['error' => 'Gagal hapus! Lelang ini sudah memiliki riwayat penawaran.']);
        }
        
        $lelang->delete();
        return redirect()->route('lelang.index')->with('success', 'Data lelang dihapus.');
    }
}