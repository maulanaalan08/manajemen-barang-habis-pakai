<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\TransaksiUnit;
use App\Models\BarangKeluar;
use App\Models\Preorder;
use Barryvdh\DomPDF\Facade\Pdf;


class CetakController extends Controller
{
    public function transaksiUnit($id_transaksi_unit)
    {
        $barang = Barang::all();
        $data = TransaksiUnit::with('unitStok')->findOrFail($id_transaksi_unit);

        $pdf = Pdf::loadView('cetak.cetak-anfra', compact('data', 'barang'))->setPaper('A4', 'landscape');
        return $pdf->stream('pengajuan-gizi.pdf');
    }

    public function transaksiLogistik($id_logistik)
    {
        // Ambil data transaksi unit
        $transaksi = TransaksiUnit::findOrFail($id_logistik);

        // Cek apakah status sudah disetujui
        if ($transaksi->status !== 'approved') {
            return redirect()->back()->with('error', 'Transaksi belum disetujui.');
        }

        // Ambil data barang_keluar sesuai barang_id dan tanggal yang sama
        $data = BarangKeluar::with('barang')
            ->where('barang_id', $transaksi->barang_id)
            ->where('tanggal', $transaksi->tanggal)
            ->orderByDesc('id_logistik')
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data pengeluaran belum tersedia.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('cetak.cetak-pengeluaran', compact('data'));
        return $pdf->stream('pengajuan-barang.pdf');
    }


    public function barangKeluar(Request $request)
    {
        $kodeInput = $request->input('kode');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $barang = Barang::select(
            'master_barang.kodebarang',
            'master_barang.namabarang',
            'master_barang.stok_gudang',
            'barang_keluar.jumlah',
            'barang_keluar.tanggal',
            'barang_keluar.user_logistik'
        )
            ->leftJoin('barang_keluar', 'master_barang.idbarang', '=', 'barang_keluar.barang_id')
            ->where('master_barang.kodebarang', 'LIKE', $kodeInput . '%')
            ->whereBetween('barang_keluar.tanggal', [$startDate, $endDate])
            ->orderBy('barang_keluar.tanggal', 'DESC')
            ->get();

        $pdf = PDF::loadView('cetak.cetak-laporan-keluar', [
            'Barang' => $barang,
            'kodeInput' => $kodeInput,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('A4', 'potrait');

        return $pdf->stream('Laporan-Barang-Keluar.pdf');
    }

    public function barangMasuk(Request $request)
    {
        $kodeInput = $request->input('kode');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $barang = Barang::select(
            'master_barang.kodebarang',
            'master_barang.namabarang',
            'barang_masuk.masuk',
            'barang_masuk.tanggal',
            'barang_masuk.user_logistik',
            'preorder.suplier'
        )
            ->leftJoin('barang_masuk', 'master_barang.idbarang', '=', 'barang_masuk.barang_id')
            ->leftJoin('preorder', 'barang_masuk.po_id', '=', 'preorder.id_po')
            ->where('master_barang.kodebarang', 'LIKE', $kodeInput . '%')
            ->whereBetween('barang_masuk.tanggal', [$startDate, $endDate])
            ->orderBy('barang_masuk.tanggal', 'DESC')
            ->get();

        $pdf = PDF::loadView('cetak.cetak-laporan-masuk', [
            'Barang' => $barang,
            'kodeInput' => $kodeInput,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('A4', 'portrait'); // Bisa diubah ke landscape jika perlu

        return $pdf->stream('Laporan-Barang-Masuk.pdf');
    }

    public function preorderCetak(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $kodeInput = $request->input('kode');

        // Query preorder antara tanggal
        $preorderQuery = Preorder::whereBetween('tanggal_po', [$start_date, $end_date]);

        // Jika ada filter kode, filter berdasarkan relasi gudang->kodebarang jika relasi tersedia
        if ($kodeInput) {
            $preorderQuery->whereHas('gudang', function ($q) use ($kodeInput) {
                $q->where('kodebarang', 'LIKE', $kodeInput . '%');
            });
        }

        $preorder = $preorderQuery->get();

        $pdf = Pdf::loadView('cetak.laporan-po-barang', [
            'Preorder' => $preorder,
            'kodeInput' => $kodeInput,
            'startDate' => $start_date,
            'endDate' => $end_date
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan-Preorder.pdf');
    }
}
