<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Masyarakat extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_masyarakat';
    protected $primaryKey = 'id_user';
    public $incrementing = true;

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'username',
        'password',
        'telp',
        'status'
    ];

    protected $hidden = ['password'];
}
