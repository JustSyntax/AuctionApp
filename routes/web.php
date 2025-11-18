<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\LelangController;
use App\Http\Controllers\HistoryController; // <-- 1. WAJIB IMPORT INI
use App\Http\Controllers\PenawaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- PUBLIC ROUTES (LOGIN & REGISTER) ---
Route::get('/', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// --- HALAMAN YANG HARUS LOGIN (SEMUA ROLE) ---
// Menggunakan middleware 'auth.session' yang baru kita buat
Route::middleware(['auth.session'])->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard/petugas', [AuthController::class, 'dashboardPetugas'])->name('dashboard.petugas');
    Route::get('/dashboard/masyarakat', [AuthController::class, 'dashboardMasyarakat'])->name('dashboard.masyarakat');

    // 2. ROUTE HISTORY (BARU)
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/penawaran', [PenawaranController::class, 'index'])->name('penawaran.index');
    Route::get('/penawaran/{id}', [PenawaranController::class, 'show'])->name('penawaran.show');
    Route::post('/penawaran/{id}/bid', [PenawaranController::class, 'store'])->name('penawaran.store');

});


// --- MASTER DATA (ADMIN & PETUGAS ONLY) ---
Route::middleware(['auth.adminpetugas'])->group(function () {

    // 1. DATA BARANG
    Route::resource('barang', BarangController::class)->except(['show']);

    // 2. DATA MASYARAKAT
    Route::patch('/masyarakat/{id}/toggle-status', [MasyarakatController::class, 'toggleStatus'])
        ->name('masyarakat.toggleStatus');
    Route::resource('masyarakat', MasyarakatController::class);
    
    // 3. DATA PETUGAS
    Route::resource('petugas', PetugasController::class)->except(['show']);

    // 4. DATA LELANG
    Route::patch('/lelang/{id}/tutup', [LelangController::class, 'update'])->name('lelang.tutup');
    Route::resource('lelang', LelangController::class)->except(['edit', 'update']); // Show diizinkan

});