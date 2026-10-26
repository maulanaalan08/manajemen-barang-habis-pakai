<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Anggaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class AnggaranController extends Controller
{
    /**
     * Halaman Perencanaan Anggaran Pengadaan Barang Habis Pakai
     */
    public function index(Request $request)
    {
        $kodeInput = $request->input('kode');

        $barang = Barang::selectRaw('
            master_barang.idbarang,
            master_barang.kodebarang,
            master_barang.namabarang,
            master_barang.idsatuan,
            master_barang.stok_gudang,
            COALESCE(SUM(barang_keluar.jumlah), 0) as pemakaian_bulan_ini,
            COALESCE(
                (SELECT harga FROM preorder
                 WHERE preorder.barang_id = master_barang.idbarang
                 ORDER BY preorder.tanggal_po DESC
                 LIMIT 1), 0) as harga_terakhir,
            COALESCE(
                (SELECT suplier FROM preorder
                 WHERE preorder.barang_id = master_barang.idbarang
                 ORDER BY preorder.tanggal_po DESC
                 LIMIT 1), "Belum Ada PO") as suplier_terakhir
        ')
        ->leftJoin('barang_keluar', function ($join) {
            $join->on('master_barang.idbarang', '=', 'barang_keluar.barang_id')
                 ->whereMonth('barang_keluar.tanggal', now()->month)
                 ->whereYear('barang_keluar.tanggal', now()->year);
        })
        ->when($kodeInput, function ($q, $k) {
            return $q->where('master_barang.kodebarang', 'LIKE', $k . '%');
        })
        ->groupBy(
            'master_barang.idbarang',
            'master_barang.kodebarang',
            'master_barang.namabarang',
            'master_barang.idsatuan',
            'master_barang.stok_gudang'
        )
        ->orderBy('master_barang.kodebarang', 'ASC')
        ->get();

        return view('anggaran.index', compact('barang', 'kodeInput'));
    }

    /**
     * Simpan Perencanaan Anggaran Barang
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:master_barang,idbarang',
            'tanggal'   => 'required|date',
            'harga'     => 'required|numeric|min:0',
            'jumlah'    => 'required|integer|min:1',
            'catatan'   => 'nullable|string|max:255',
        ]);

        Anggaran::create([
            'barang_id' => $request->barang_id,
            'tanggal'   => $request->tanggal,
            'harga'     => $request->harga,
            'jumlah'    => $request->jumlah,
            'catatan'   => $request->catatan ?? '-',
        ]);

        return redirect()->route('anggaran')
            ->with('success', 'Rencana anggaran pembelian barang berhasil ditambahkan!');
    }

    /**
     * Halaman Rekapitulasi & Laporan Anggaran Pembelian
     */
    public function pembelian(Request $request)
    {
        $kodeInput = $request->input('kode');
        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));

        $anggaranQuery = Anggaran::selectRaw('
            anggaran.*,
            master_barang.kodebarang,
            master_barang.namabarang,
            master_barang.idsatuan,
            (anggaran.harga * anggaran.jumlah) as total_biaya,
            COALESCE(
                (SELECT suplier FROM preorder
                 WHERE preorder.barang_id = master_barang.idbarang
                 ORDER BY preorder.tanggal_po DESC
                 LIMIT 1), "Belum Ada PO") as suplier_terakhir
        ')
        ->join('master_barang', 'anggaran.barang_id', '=', 'master_barang.idbarang')
        ->whereMonth('anggaran.tanggal', $month)
        ->whereYear('anggaran.tanggal', $year);

        if ($kodeInput) {
            $anggaranQuery->where('master_barang.kodebarang', 'LIKE', $kodeInput . '%');
        }

        $anggaranList = $anggaranQuery->orderBy('anggaran.tanggal', 'DESC')->get();
        $totalAnggaran = $anggaranList->sum('total_biaya');
        $totalKuantitas = $anggaranList->sum('jumlah');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('anggaran.pembelian', compact(
            'anggaranList',
            'totalAnggaran',
            'totalKuantitas',
            'kodeInput',
            'month',
            'year',
            'namaBulan'
        ));
    }

    /**
     * Cetak Laporan Rekapitulasi Anggaran Pembelian (PDF)
     */
    public function cetak(Request $request)
    {
        $kodeInput = $request->input('kode');
        $month = (int) $request->input('month', date('n'));
        $year = (int) $request->input('year', date('Y'));

        $anggaranQuery = Anggaran::selectRaw('
            anggaran.*,
            master_barang.kodebarang,
            master_barang.namabarang,
            master_barang.idsatuan,
            (anggaran.harga * anggaran.jumlah) as total_biaya,
            COALESCE(
                (SELECT suplier FROM preorder
                 WHERE preorder.barang_id = master_barang.idbarang
                 ORDER BY preorder.tanggal_po DESC
                 LIMIT 1), "Belum Ada PO") as suplier_terakhir
        ')
        ->join('master_barang', 'anggaran.barang_id', '=', 'master_barang.idbarang')
        ->whereMonth('anggaran.tanggal', $month)
        ->whereYear('anggaran.tanggal', $year);

        if ($kodeInput) {
            $anggaranQuery->where('master_barang.kodebarang', 'LIKE', $kodeInput . '%');
        }

        $anggaranList = $anggaranQuery->orderBy('master_barang.kodebarang', 'ASC')->get();
        $totalAnggaran = $anggaranList->sum('total_biaya');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $pdf = Pdf::loadView('cetak.cetak-anggaran-pembelian', [
            'anggaranList'  => $anggaranList,
            'totalAnggaran' => $totalAnggaran,
            'month'         => $month,
            'year'          => $year,
            'namaBulan'     => $namaBulan,
            'kodeInput'     => $kodeInput,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan-Anggaran-Pembelian-' . ($namaBulan[$month] ?? $month) . '-' . $year . '.pdf');
    }
}

