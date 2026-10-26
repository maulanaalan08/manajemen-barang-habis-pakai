<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Logistik;
use App\Models\BarangGizi;
use App\Models\Gudang;
use App\Models\Gizi;
use App\Models\OpnameBarang;

class OpnameController extends Controller
{
    public function index3()
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Barang
        return view('gudang.opname-barang', ['Barang' => $barang]);
    }

    public function tambah (Request $request)
    {
        $barang = Barang::all();
        $opname = OpnameBarang::all();

        $request->validate([
                'barang_id' => 'required|exists:master_barang,idbarang',
                'tanggal' => 'required|date',
                'stok_opname' => 'required',
                'keterangan' => 'required',
            ]);
            $barangData = Barang::where('idbarang', $request->barang_id)->first();

            $hasil = $request->stok_opname - $barangData->stok_gudang;

            OpnameBarang::create ([
                'barang_id' => $request->barang_id,
                'tanggal' => $request->tanggal,
                'stok_opname' => $request->stok_opname,
                'hasil' => $hasil,
                'keterangan' =>$request->keterangan,
            ]);

            Barang::where('idbarang', $request->barang_id)->increment('stok_gudang', $hasil);

            return redirect()->route('opname.barang')
                ->with('success', 'Data opname berhasil ditambahkan')->with('Barang', $barang)->with('OpnameBarang', $opname);

    }
}

