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
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->bigIncrements('id_masuk');
            $table->unsignedBigInteger('po_id'); // Tambahkan ini dulu
            $table->unsignedBigInteger('barang_id'); // Tambahkan ini dulu
            $table->integer('masuk');
            $table->date('tanggal');

            // Relasi foreign key
            $table->foreign('barang_id')->references('idbarang')->on('master_barang')->onDelete('cascade');
            $table->foreign('po_id')->references('id_po')->on('preorder')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
