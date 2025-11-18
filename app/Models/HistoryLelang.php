<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryLelang extends Model
{
    use HasFactory;
    
    // INI DIA PERBAIKANNYA
    protected $table = 'history_lelang'; // <-- Disesuaikan jadi 'history_lelang'
    
    protected $primaryKey = 'id_history';

    protected $fillable = ['id_lelang', 'id_barang', 'id_user', 'penawaran_harga'];

    // Relasi ke Masyarakat (penawar)
    public function masyarakat()
    {
        return $this->belongsTo(Masyarakat::class, 'id_user', 'id_user');
    }

    // Relasi ke Lelang
    public function lelang()
    {
        return $this->belongsTo(Lelang::class, 'id_lelang');
    }
}