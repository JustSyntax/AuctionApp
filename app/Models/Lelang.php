<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lelang extends Model
{
    use HasFactory;

    protected $table = 'tb_lelang';
    protected $primaryKey = 'id_lelang';

    protected $fillable = [
        'id_barang',
        'tgl_lelang',
        'harga_akhir',
        'id_petugas',
        'status',
    ];

    /**
     * INI TAMBAHANNYA
     * Otomatis ubah kolom tgl_lelang menjadi Carbon (objek tanggal)
     */
    protected $casts = [
        'tgl_lelang' => 'date',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // Relasi ke Petugas
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    // Relasi ke SEMUA history penawaran
    public function history()
    {
        return $this->hasMany(HistoryLelang::class, 'id_lelang')->orderBy('penawaran_harga', 'DESC');
    }

    // Relasi ke SATU history tertinggi (Pemenangnya)
    public function pemenang()
    {
        return $this->hasOne(HistoryLelang::class, 'id_lelang')->orderBy('penawaran_harga', 'DESC');
    }
}