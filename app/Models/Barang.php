<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model.
     *
     * @var string
     */
    protected $table = 'tb_barang';

    /**
     * Primary key dari tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_barang';

    /**
     * Kolom yang bisa diisi secara massal (mass assignable).
     *
     * @var array
     */
    protected $fillable = [
        'nama_barang',
        'tgl',
        'harga_awal',
        'deskripsi_barang',
        'gambar',
    ];

    public function lelang()
    {
        return $this->hasOne(Lelang::class, 'id_barang', 'id_barang');
    }
    /**
     * Mengubah format 'tgl' menjadi Carbon instance
     *
     * @var array
     */
    protected $casts = [
        'tgl' => 'date',
    ];
}