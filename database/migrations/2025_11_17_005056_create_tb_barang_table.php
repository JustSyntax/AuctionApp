<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->string('nama_barang', 25);
            $table->date('tgl');
            $table->integer('harga_awal');
            $table->string('deskripsi_barang', 100);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_barang');
    }
};
