<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_lelang', function (Blueprint $table) {
            $table->increments('id_lelang');
            $table->unsignedInteger('id_barang');
            $table->date('tgl_lelang');
            $table->integer('harga_akhir')->nullable();
            $table->unsignedInteger('id_petugas');
            $table->enum('status', ['dibuka', 'ditutup']);
            $table->timestamps();

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('tb_barang')
                ->onDelete('cascade');

            $table->foreign('id_petugas')
                ->references('id_petugas')
                ->on('tb_petugas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_lelang');
    }
};
