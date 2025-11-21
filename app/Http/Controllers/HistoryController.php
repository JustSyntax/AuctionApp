<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lelang;
use App\Models\HistoryLelang;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        if (!session()->has('role')) {
            return redirect()->route('login');
        }

        $role = session('role');
        $search = $request->input('search');

        // === 1. LOGIC MASYARAKAT (Lihat tawaran sendiri) ===
        if ($role == 'masyarakat') {
            $filterStatus = $request->input('filter_status');

            // Join tabel biar bisa filter status menang/kalah & pagination lancar
            $query = HistoryLelang::select('history_lelang.*')
                ->join('tb_lelang', 'history_lelang.id_lelang', '=', 'tb_lelang.id_lelang')
                ->join('tb_barang', 'tb_lelang.id_barang', '=', 'tb_barang.id_barang')
                ->with(['lelang.barang', 'lelang.petugas'])
                ->where('history_lelang.id_user', session('user_id'))
                ->orderBy('history_lelang.created_at', 'desc');

            // Filter Search (Nama Barang)
            if ($search) {
                $query->where('tb_barang.nama_barang', 'like', "%{$search}%");
            }

            // Filter Status
            if ($filterStatus == 'pending') {
                $query->where('tb_lelang.status', 'dibuka');
            } elseif ($filterStatus == 'menang') {
                $query->where('tb_lelang.status', 'ditutup')
                      ->whereColumn('tb_lelang.harga_akhir', 'history_lelang.penawaran_harga');
            } elseif ($filterStatus == 'kalah') {
                $query->where('tb_lelang.status', 'ditutup')
                      ->whereColumn('tb_lelang.harga_akhir', '!=', 'history_lelang.penawaran_harga');
            }

            $myBids = $query->paginate(10)->withQueryString();
            
            return view('history.index', compact('role', 'myBids', 'filterStatus', 'search'));
        } 
        
        // === 2. LOGIC ADMIN & PETUGAS (Lihat lelang selesai) ===
        else {
            $dateStart = $request->input('date_start');
            $dateEnd   = $request->input('date_end');

            $query = Lelang::with(['barang', 'pemenang.masyarakat'])
                ->where('status', 'ditutup') // Hanya yang sudah selesai
                ->orderBy('updated_at', 'desc');

            // --- PERBAIKAN DISINI ---
            // Jika yang login PETUGAS, kunci query hanya ke data dia sendiri
            if ($role == 'petugas') {
                $query->where('id_petugas', session('user_id'));
            }
            // Jika Administrator, biarkan (bisa lihat semua)
            
            // ------------------------

            // Filter Search (Nama Barang)
            if ($search) {
                $query->whereHas('barang', function($q) use ($search) {
                    $q->where('nama_barang', 'like', "%{$search}%");
                });
            }

            // Filter Tanggal
            if ($dateStart) {
                $query->whereDate('updated_at', '>=', $dateStart);
            }
            if ($dateEnd) {
                $query->whereDate('updated_at', '<=', $dateEnd);
            }

            $lelangs = $query->paginate(10)->withQueryString();

            return view('history.index', compact('role', 'lelangs', 'search', 'dateStart', 'dateEnd'));
        }
    }
}