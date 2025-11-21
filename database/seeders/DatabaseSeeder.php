<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\Level;
use App\Models\Masyarakat;

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
        // 3. BUAT AKUN PETUGAS
        // Cek dulu biar ga duplikat username
        if (!Petugas::where('username', 'petugas')->exists()) {
            Petugas::create([
                'nama_petugas' => 'Petugas',      // Nama
                'username'     => 'petugas',      // Username
                'password'     => Hash::make('12345678'), // Password (1-8)
                'id_level'     => $levelPetugas->id_level, // Ambil ID dari level Petugas tadi
            ]);
            
            $this->command->info('User Petugas berhasil dibuat! (Pass: 12345678)');
        } else {
            $this->command->warn('User Petugas sudah ada, skip pembuatan.');
        }

        if (!Masyarakat::where('username', 'masyarakat')->exists()) {
            Masyarakat::create([
                'nik'          => '3216549870123456', // NIK
                'nama_lengkap' => 'Masyarakat',      // Nama
                'username'     => 'masyarakat',      // Username
                'password'     => Hash::make('12345678'), // Password (1-8)
                'telp'         => '081234567890',   // No. Telp
                'alamat'       => 'Jl. Cimahi No.123', // Alamat
                'status'       => 'aktif',           // Status
            ]);
            
            $this->command->info('User Masyarakat berhasil dibuat! (Pass: 12345678)');
        } else {
            $this->command->warn('User Masyarakat sudah ada, skip pembuatan.');
        }
    }
}