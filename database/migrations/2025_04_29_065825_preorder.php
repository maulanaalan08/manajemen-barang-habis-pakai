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
        Schema::create('preorder', function (Blueprint $table) {
            $table->bigIncrements('id_po');
            $table->unsignedBigInteger('barang_id');
            $table->string('nomor_po');
            $table->integer('jumlah_po');
            $table->integer('harga');
            $table->date('tanggal_po');
            $table->date('expired_po');
            $table->string('suplier');

            $table->foreign('barang_id')->references('idbarang')->on('master_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preorder');
    }
};
