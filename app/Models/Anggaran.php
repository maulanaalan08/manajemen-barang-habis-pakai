<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $table = 'anggaran';
    protected $primaryKey = 'id_anggaran';
    public $timestamps = false;

    protected $fillable = [
        'barang_id',
        'harga',
        'jumlah',
        'tanggal',
        'catatan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'idbarang');
    }
}
