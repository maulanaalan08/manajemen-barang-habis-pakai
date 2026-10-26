<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpnameBarang extends Model
{
    use HasFactory;
    protected $table = "opname_barang";
    public $timestamps = false;
    protected $fillable = ['barang_id', 'tanggal', 'stok_opname', 'hasil', 'keterangan'];
    protected $primaryKey = 'id_opname';

    public function stok()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'idbarang');
    }
}
