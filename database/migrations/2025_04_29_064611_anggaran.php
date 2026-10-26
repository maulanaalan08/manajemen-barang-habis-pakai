<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggaran', function (Blueprint $table) {
            $table->bigIncrements('id_anggaran');
            $table->unsignedBigInteger('barang_id'); // Tambahkan ini dulu
            $table->integer('harga');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->string('catatan');

            $table->foreign('barang_id')->references('idbarang')->on('master_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran');   
    }
};
