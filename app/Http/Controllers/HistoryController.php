<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lelang;
use App\Models\HistoryLelang;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        // Pastikan login (proteksi ganda selain middleware)
        if (!session()->has('role')) {
            return redirect()->route('login');
        }

        $role = session('role');

        // LOGIC 1: MASYARAKAT
        // Hanya melihat history penawaran yang DIA lakukan sendiri
        if ($role == 'masyarakat') {
            $myBids = HistoryLelang::with(['lelang.barang', 'lelang.petugas'])
                ->where('id_user', session('user_id'))
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('history.index', compact('role', 'myBids'));
        } 
        
        // LOGIC 2: ADMIN & PETUGAS
        // Melihat semua lelang yang sudah SELESAI (Ditutup) sebagai laporan
        else {
            $lelangs = Lelang::with(['barang', 'pemenang.masyarakat'])
                ->where('status', 'ditutup')
                ->orderBy('updated_at', 'desc') // Urutkan dari yang baru ditutup
                ->get();

            return view('history.index', compact('role', 'lelangs'));
        }
    }
}