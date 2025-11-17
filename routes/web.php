<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// LANDING + LOGIN PAGE
Route::get('/', [AuthController::class, 'loginPage'])->name('login');

// LOGIN PROCESS
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// REGISTER PROCESS (MASYARAKAT ONLY)
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// LOGOUT
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// DASHBOARD PETUGAS / ADMIN (HARUS LOGIN)
    Route::get('/dashboard/petugas', [AuthController::class, 'dashboardPetugas'])->name('dashboard.petugas');
    Route::get('/dashboard/masyarakat', [AuthController::class, 'dashboardMasyarakat'])->name('dashboard.masyarakat');
