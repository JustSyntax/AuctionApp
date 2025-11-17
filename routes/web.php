<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MasyarakatController; // <-- Jangan lupa import ini

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


// --- DASHBOARD ROUTES ---
// (Akses dicek di Controller atau Middleware RoleMiddleware jika nanti dipasang)
Route::get('/dashboard/petugas', [AuthController::class, 'dashboardPetugas'])->name('dashboard.petugas');
Route::get('/dashboard/masyarakat', [AuthController::class, 'dashboardMasyarakat'])->name('dashboard.masyarakat');


// --- MASTER DATA (ADMIN & PETUGAS ONLY) ---
// Semua route di dalam sini otomatis kena middleware 'auth.adminpetugas'
Route::middleware(['auth.adminpetugas'])->group(function () {

    // 1. DATA BARANG
    Route::resource('barang', BarangController::class)->except(['show']);

    // 2. DATA MASYARAKAT
    // Route khusus Toggle Status (Harus didefinisikan SEBELUM resource)
    Route::patch('/masyarakat/{id}/toggle-status', [MasyarakatController::class, 'toggleStatus'])
        ->name('masyarakat.toggleStatus');
        
    // Resource default (index, create, store, edit, update, destroy)
    Route::resource('masyarakat', MasyarakatController::class);
    Route::resource('petugas', App\Http\Controllers\PetugasController::class)->except(['show']);

});