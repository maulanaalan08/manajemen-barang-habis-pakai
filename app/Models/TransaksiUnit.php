<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiUnit extends Model
{
    use HasFactory;
    protected $table = 'transaksi_unit';
    protected $fillable = ['barang_unit_id', 'barang_id', 'masuk', 'tanggal', 'status', 'nama_user', 'user_unit'];
    protected $primaryKey = 'id_transaksi_unit';
    public $timestamps = false;  // Menonaktifkan timestamps


    public function unitBarang()
    {
        return $this->belongsTo(BarangUnit::class, 'barang_unit_id', 'id_barang_unit');
    }

    public function unitStok()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'idbarang');
    }
}
