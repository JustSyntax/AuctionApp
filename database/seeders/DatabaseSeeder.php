<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\Level;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT DATA LEVEL DULU
        // Kita pakai firstOrCreate biar kalau di-seed 2x gak error duplikat
        $levelAdmin = Level::firstOrCreate(
            ['level' => 'administrator'], // Cari data ini
            ['level' => 'administrator']  // Kalau ga ada, buat baru
        );

        $levelPetugas = Level::firstOrCreate(
            ['level' => 'petugas'],
            ['level' => 'petugas']
        );

        // 2. BUAT AKUN ADMIN
        // Cek dulu biar ga duplikat username
        if (!Petugas::where('username', 'admin')->exists()) {
            Petugas::create([
                'nama_petugas' => 'Admin',      // Nama
                'username'     => 'admin',      // Username
                'password'     => Hash::make('12345678'), // Password (1-8)
                'id_level'     => $levelAdmin->id_level, // Ambil ID dari level Administrator tadi
            ]);
            
            $this->command->info('User Admin berhasil dibuat! (Pass: 12345678)');
        } else {
            $this->command->warn('User Admin sudah ada, skip pembuatan.');
        }
    }
}