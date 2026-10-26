<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\TransaksiUnit;

use Illuminate\Support\Facades\DB;


class PengadaanController extends Controller
{
    public function adminIndex2()
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Unit
        return view('gudang.master', ['Barang' => $barang]);
    }
    public function adminIndex3()
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Unit
        return view('gudang.tambah-barang', ['Barang' => $barang]);
    }

    public function tambahBarang()
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Unit
        return view('gudang.tambah-master', ['Barang' => $barang]);
    }

    public function barang(Request $request)
    {
        $request->validate([
            'kodebarang' => 'required|string|unique:master_barang,kodebarang',
            'namabarang' => 'required|string',
            'idsatuan' => 'required|string',
        ]);

        Barang::create([
            'kodebarang' => $request->kodebarang,
            'namabarang' => $request->namabarang,
            'idsatuan' => $request->idsatuan,
            'stok_gudang' => 0
        ]);

        return redirect()->back()->with('success', 'Barang baru berhasil ditambahkan.');
    }

    public function aproval(Request $request)
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

        $barang = $query->get(); // Ambil data setelah filter
        return view('gudang.barang-keluar', ['Barang' => $barang]);
    }

    public function terima(Request $request, $id_transaksi_unit)
    {
        $approval = TransaksiUnit::findOrFail($id_transaksi_unit);

        if ($approval->status !== 'pending') {
            return redirect()->route('aproval.barang')->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        $request->validate([
            'jumlah_disetujui' => 'required|integer|min:1|max:' . $approval->masuk,
        ]);

        $jumlahDisetujui = $request->jumlah_disetujui;
        $stokGudang = Barang::where('idbarang', $approval->barang_id)->value('stok_gudang');

        if ($stokGudang < $jumlahDisetujui) {
            return redirect()->route('aproval.barang')->with('error', 'Stok di gudang tidak mencukupi.');
        }

        // Update stok di tabel Gizi dan Barang
        // Gizi::where('id_gizi', $approval->gizi_id)->increment('stok_gizi', $jumlahDisetujui);
        Barang::where('idbarang', $approval->barang_id)->decrement('stok_gudang', $jumlahDisetujui);

        // Simpan data ke tabel barang_keluar
        DB::table('barang_keluar')->insert([
            'barang_id' => $approval->barang_id,
            'jumlah' => $jumlahDisetujui,
            'tanggal' => $approval->tanggal,
            'user_logistik' => auth()->user()->username, // nama user yang login
        ]);

        // Ubah status approval menjadi disetujui dan simpan jumlah yang disetujui
        $approval->update([
            'status' => 'approved',
            'masuk' => $jumlahDisetujui
        ]);

        return redirect()->route('transaksi.logistik', ['id' => $approval->id_transaksi_unit]);
    }



    public function laporan(Request $request)
    {
        $kodeInput = $request->input('kode');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $barang = Barang::selectRaw('master_barang.kodebarang, master_barang.namabarang,master_barang.stok_gudang,
                                    SUM(barang_keluar.jumlah) as total_keluar')
            ->leftJoin('barang_keluar', 'master_barang.idbarang', '=', 'barang_keluar.barang_id')
            ->where('master_barang.kodebarang', 'LIKE', $kodeInput . '%')
            ->whereBetween('barang_keluar.tanggal', [$startDate, $endDate])
            ->groupBy('master_barang.kodebarang', 'master_barang.namabarang', 'master_barang.stok_gudang')
            ->orderBy('total_keluar', 'DESC')
            ->get();

        return view(
            'cetak.laporan-barang-keluar',
            [
                'Barang' => $barang,
                'kodeInput ' => $kodeInput,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]
        );
    }



    public function laporanMasuk(Request $request)
    {
        $kodeInput = $request->input('kode');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $barang = DB::table('barang_masuk')
            ->join('master_barang', 'barang_masuk.barang_id', '=', 'master_barang.idbarang')
            ->join('preorder', 'barang_masuk.po_id', '=', 'preorder.id_po')
            ->select(
                'master_barang.kodebarang',
                'master_barang.namabarang',
                'barang_masuk.masuk',
                'barang_masuk.tanggal',
                'barang_masuk.user_logistik',
                'preorder.suplier'
            )
            ->where('master_barang.kodebarang', 'LIKE', $kodeInput . '%')
            ->whereBetween('barang_masuk.tanggal', [$startDate, $endDate])
            ->orderBy('barang_masuk.tanggal', 'DESC')
            ->get();

        return view('cetak.laporan-barang-masuk', [
            'Barang' => $barang,
            'kodeInput' => $kodeInput,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
