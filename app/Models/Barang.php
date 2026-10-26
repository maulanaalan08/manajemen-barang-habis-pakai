<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = "master_barang";
    protected $primaryKey = 'idbarang';
    protected $fillable = [
        'kodebarang',
        'namabarang',
        'idsatuan',
        'stok_gudang',
    ];
    public $timestamps = false;

    public function logistik()
    {
        return $this->hasMany(BarangKeluar::class, 'barang_id', 'idbarang');
    }

    public function stokUnit()
    {
        return $this->hasMany(TransaksiUnit::class, 'barang_id', 'idbarang');
    }

    public function barang()
    {
        return $this->hasMany(Preorder::class, 'barang_id', 'idbarang');
    }

    public function masuk()
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id', 'idbarang');
    }

    public function listbarang()
    {
        return $this->hasMany(Anggaran::class, 'barang_id', 'idbarang');
    }

    public function gudang()
    {
        return $this->belongsTo(Preorder::class, 'barang_id', 'idbarang');
    }

    public function opname()
    {
        return $this->hasMany(OpnameBarang::class, 'barang_id', 'idbarang');
    }

}
