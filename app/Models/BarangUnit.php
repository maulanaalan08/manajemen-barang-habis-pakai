<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangUnit extends Model
{
    use HasFactory;
    protected $table = 'barang_unit';
    protected $primaryKey = 'id_barang_unit';
    protected $fillable = ['barang_id'];
    public $timestamps = false;  // Menonaktifkan timestamps


    public function stokUnit()
    {
        return $this->hasMany(TransaksiUnit::class, 'barang_unit_id', 'id_barang_unit');
    }

    public function barangUnit()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'idbarang');
    }
}
