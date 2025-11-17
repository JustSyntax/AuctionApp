<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('history_lelang', function (Blueprint $table) {
            $table->increments('id_history');
            $table->unsignedInteger('id_lelang');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_user');
            $table->integer('penawaran_harga');
            $table->timestamps();

            $table->foreign('id_lelang')
                ->references('id_lelang')
                ->on('tb_lelang')
                ->onDelete('cascade');

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('tb_barang')
                ->onDelete('cascade');

            $table->foreign('id_user')
                ->references('id_user')
                ->on('tb_masyarakat')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_lelang');
    }
};