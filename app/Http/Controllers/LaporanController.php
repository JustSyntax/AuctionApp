<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lelang;
use App\Models\Petugas;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Dropdown Admin
        $petugasList = [];
        if (session('role') == 'administrator') {
            $petugasList = Petugas::whereHas('level', function($q){
                $q->where('level', 'petugas');
            })->get();
        }

        // 2. Data Laporan
        $laporan = $this->getLaporanData($request);
        
        // 3. Hitung Total
        $totalPendapatan = $laporan->sum('harga_akhir');

        return view('petugas.laporan.index', compact('laporan', 'petugasList', 'totalPendapatan'));
    }

    public function print(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $laporan = $this->getLaporanData($request);
        $totalPendapatan = $laporan->sum('harga_akhir');
        
        $pdf = Pdf::loadView('petugas.laporan.print', compact('laporan', 'totalPendapatan'))
                  ->setPaper('a4', 'landscape');

        // === FIX CACHE: Tambahkan Jam-Menit-Detik di nama file ===
        $fileName = 'Laporan-Lelang-' . date('d-m-Y_H-i-s') . '.pdf';
        
        return $pdf->download($fileName, ['Attachment' => false]);
    }

    private function getLaporanData(Request $request)
    {
        $query = Lelang::with(['barang', 'petugas', 'pemenang.masyarakat'])
            ->where('status', 'ditutup')
            ->orderBy('updated_at', 'desc');

        if (session('role') == 'administrator') {
            if ($request->has('id_petugas') && $request->id_petugas != '') {
                $query->where('id_petugas', $request->id_petugas);
            }
        } else {
            $query->where('id_petugas', session('user_id'));
        }

        // Filter Tanggal (tetap berdasarkan kapan ditutup/updated_at agar laporan akurat)
        if ($request->filled('date_start')) {
            $query->whereDate('updated_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('updated_at', '<=', $request->date_end);
        }

        return $query->get();
    }
}