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
        Schema::create('opname_barang', function (Blueprint $table) {
            $table->bigIncrements('id_opname');
            $table->unsignedBigInteger('barang_id');
            $table->date('tanggal');
            $table->integer('stok_opname');
            $table->integer('hasil');
            $table->string('keterangan');

            $table->foreign('barang_id')->references('idbarang')->on('master_barang')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opname_barang');
    }
};
