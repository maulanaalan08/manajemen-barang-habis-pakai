<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\TransaksiUnit;


class AnfraController extends Controller
{
    public function adminIndex3()
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Barang
        $barangunit = BarangUnit::all(); // Ambil semua data dari tabel Gizi
        return view('transaksi.anfra', ['BarangUnit' => $barangunit, 'Barang' => $barang]);
    }

    public function tambah()
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Barang
        $barangunit = BarangUnit::all(); // Ambil semua data dari tabel Gizi
        return view('transaksi.barang-unit', ['BarangUnit' => $barangunit, 'Barang' => $barang]);
    }

    public function barang(Request $request)
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Unit
        // Validasi input
        $validated = $request->validate([
            'barang_id' => 'required|integer|exists:master_barang,idbarang'
        ]);

        // Cek apakah barang_id sudah ada dalam tabel barang_terpilih
        $existingBarang = BarangUnit::where('barang_id', $validated['barang_id'])->first();

        if ($existingBarang) {
            // Jika barang_id sudah ada, kembalikan error
            return redirect()->back()->withErrors(['barang_id' => 'Barang ini sudah ada.']);
        }
        // Simpan ke database
        BarangUnit::create([
            'barang_id' => $validated['barang_id'],
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('anfra')->with('success', 'Data berhasil ditambahkan!')->with('Barang', $barang);
    }

public function stok(Request $request)
{
    // $data = TransaksiUnit::with('barang')->get();

    $request->validate([
        'barang_unit_id' => 'required|exists:barang_unit,id_barang_unit',
        'barang_id'      => 'required|exists:master_barang,idbarang',
        'tanggal'        => 'required|date',
        'masuk'          => 'required|integer|min:1',
    ]);

    $stokGudang = Barang::where('idbarang', $request->barang_id)->value('stok_gudang');

    if ($stokGudang < $request->masuk) {
        return redirect()->route('anfra')
            ->with('error', 'Stok di gudang tidak mencukupi untuk diajukan');
    }

    // Simpan data sebagai pengajuan untuk approval
    $data = TransaksiUnit::create([
        'barang_unit_id' => $request->barang_unit_id,
        'barang_id'      => $request->barang_id,
        'tanggal'        => $request->tanggal,
        'masuk'          => $request->masuk,
        'status'         => 'pending',
        'nama_user'      => auth()->user()->username,
        'user_unit'      => auth()->user()->name,
    ]);

    // Redirect ke route cetak PDF
    return redirect()->route('anfra')
        ->with('success', 'Pengajuan Barang berhasil dibuat dan tunggu di approve untuk dicetak.');
}

public function prosesTransaksi(Request $request)
{
    $status = $request->input('status'); // Ambil status dari request
    $query = TransaksiUnit::query();

    // Filter berdasarkan status jika ada
    if ($status) {
        if ($status == 'sudah_diterima') {
            $query->where('status', 'approved');
        } elseif ($status == 'belum_diterima') {
            $query->where('status', 'pending');
        }
    }
    $user = auth()->user();
    if (!$user->hasRole('admin') && !$user->hasRole('logistik')) {
        $query->where('user_unit', $user->name);
    }

    $barang = $query->get(); // Ambil data setelah filter
    return view('transaksi.proses-transaksi', ['Barang' => $barang]);
}

}
