<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $table = "barang_masuk";
    public $timestamps = false;
    protected $primaryKey = 'id_masuk';
    protected $fillable = [
        'po_id',
        'barang_id',
        'masuk',
        'tanggal',
        'user_logistik',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'idbarang');
    }
    
    public function preorder()
    {
        return $this->belongsTo(Preorder::class, 'po_id', 'id_po');
    }
}
