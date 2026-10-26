<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preorder extends Model
{
    use HasFactory;
    protected $table = "preorder";
    public $timestamps = false;
    protected $primaryKey = 'id_po';
    protected $fillable = [
        'barang_id',
        'nomor_po',
        'jumlah_po',
        'harga',
        'tanggal_po',
        'expired_po',
        'suplier'
    ];
    public function gudang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'idbarang');
    }
    public function barangmasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'po_id', 'id_po');
    }
}
