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
        Schema::create('transaksi_unit', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi_unit');
            $table->unsignedBigInteger('barang_unit_id'); // Tambahkan ini dulu
            $table->unsignedBigInteger('barang_id'); // Tambahkan ini dulu
            $table->integer('masuk');
            $table->date('tanggal');
            $table->string('status');
            $table->string('nama_user');
            $table->string('user_unit');

            // Relasi foreign key
            $table->foreign('barang_unit_id')->references('id_barang_unit')->on('barang_unit')->onDelete('cascade');
            $table->foreign('barang_id')->references('idbarang')->on('master_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_unit');
    }
};
