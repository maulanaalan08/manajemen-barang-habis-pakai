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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->bigIncrements('id_logistik');
            $table->unsignedBigInteger('barang_id'); // Tambahkan ini dulu
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->string('user_logistik');            
            // Relasi foreign key
            $table->foreign('barang_id')->references('idbarang')->on('master_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
        //
    }
};
