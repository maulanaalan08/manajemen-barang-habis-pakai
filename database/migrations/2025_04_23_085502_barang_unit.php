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
        Schema::create('barang_unit', function (Blueprint $table) {
            $table->bigIncrements('id_barang_unit');
            $table->unsignedBigInteger('barang_id'); // Tambahkan ini dulu
            // Relasi foreign key
            $table->foreign('barang_id')->references('idbarang')->on('master_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_unit');
    }
};
