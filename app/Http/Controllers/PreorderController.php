<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Preorder;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;

class PreorderController extends Controller
{

    public function view()
    {
        $preorder = Preorder::all(); // Ambil semua data dari tabel Gizi
        return view('pengadaan.list-po', ['Preorder' => $preorder]);
    }

    public function index()
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Barang
        $preorder = Preorder::all(); // Ambil semua data dari tabel Gizi
        return view('pengadaan.po-barang', ['Preorder' => $preorder, 'Barang' => $barang]);
    }


    public function tambah(Request $request)
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Barang
        $preorder = Preorder::all(); // Ambil semua data dari tabel Gizi

        $validatedData = $request->validate([
            'nomor_po'   => 'required|string|max:255',
            'tanggal_po' => 'required|date',
            'suplier'    => 'required|string|max:255',
            'barang_id'  => 'required|array',
            'barang_id.*' => 'exists:master_barang,idbarang',
            'jumlah_po'  => 'required|array',
            'jumlah_po.*' => 'integer|min:1',
            'harga'      => 'required|array',
            'harga.*'    => 'numeric|min:1',
            'expired_po' => 'required|date|after_or_equal:tanggal_po',
        ]);

        // Simpan data opname ke database dengan kolom 'hasil' terisi otomatis
        foreach ($request->barang_id as $index => $barang_id) {
            Preorder::create([
                'nomor_po'   => $request->nomor_po,
                'tanggal_po' => $request->tanggal_po,
                'suplier'    => $request->suplier,
                'barang_id'  => $barang_id,
                'jumlah_po'  => $request->jumlah_po[$index],
                'harga'      => $request->harga[$index],
                'expired_po' => $request->expired_po,
            ]);
        }

        // dd($request->all());
        return redirect()->route('po.barang')
            ->with('success', 'Data Opname berhasil ditambahkan')->with('Barang', $barang)->with('Preorder', $preorder);
    }

    public function pomasuk(Request $request)
    {
        $start_date = $request->input('start_date'); // Format: YYYY-MM-DD
        $end_date = $request->input('end_date'); // Format: YYYY-MM-DD
        $status = $request->input('status');

        $barang = Barang::all(); // Ambil semua data dari tabel Unit
        // $preorder = Preorder::all();
        $preorderQuery = Preorder::whereBetween('tanggal_po', [$start_date, $end_date]);
        // Tambahkan filter status jika ada
        if ($status == "sudah_diterima") {
            $preorderQuery->where('jumlah_po', 0);
        } elseif ($status == "belum_diterima") {
            $preorderQuery->where('jumlah_po', '>', 0);
        }

        $preorder = $preorderQuery->get();
        return view('gudang.barang-masuk', ['Barang' => $barang, 'Preorder' => $preorder]);
    }

    public function barangMasuk(Request $request)
    {
        $barang = Barang::all(); // Ambil semua data dari tabel Barang
        $preorder = Preorder::all(); // Ambil semua data dari tabel Gizi

        $request->validate([
            'po_id' => 'required|exists:preorder,id_po',
            'tanggal' => 'required|date',
            'masuk' => 'required',

        ]);

        $stokGudang = Preorder::where('id_po', $request->po_id)->value('jumlah_po');

        if ($stokGudang < $request->masuk) {
            return redirect()->route('po.masuk')
                ->with('error', 'Stok Masuk melebihi jumlah PO .');
        }

        $data = $request->all();
        $data['user_logistik'] = auth()->user()->username;

        BarangMasuk::create($data);
        Barang::where('idbarang', $request->barang_id)->increment('stok_gudang', $request->masuk);
        Preorder::where('id_po', $request->po_id)->decrement('jumlah_po', $request->masuk);


        return redirect()->route('po.masuk')
            ->with('success', 'Data Barang Masuk berhasil ditambahkan')->with('Barang', $barang)->with('Preorder', $preorder);
    }

    public function laporan(Request $request)
    {
        $start_date = $request->input('start_date'); // Format: YYYY-MM-DD
        $end_date = $request->input('end_date'); // Format: YYYY-MM-DD

        $barang = Barang::all(); // Ambil semua data dari tabel Unit
        $preorder = Preorder::whereBetween('tanggal_po', [$start_date, $end_date])->get();

        return view('pengadaan.laporan-preorder', ['Barang' => $barang, 'Preorder' => $preorder]);
    }
}
