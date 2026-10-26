<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PrediksiController extends Controller
{
    /**
     * Halaman Utama Pemilihan Barang & Pergerakan Stok untuk Prediksi
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kodeKategori = $request->input('kode');

        $query = Barang::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('namabarang', 'LIKE', '%' . $search . '%')
                  ->orWhere('kodebarang', 'LIKE', '%' . $search . '%');
            });
        }

        if ($kodeKategori) {
            $query->where('kodebarang', 'LIKE', $kodeKategori . '%');
        }

        $barangList = $query->orderBy('namabarang', 'ASC')->paginate(15)->withQueryString();
        $allBarang = Barang::orderBy('namabarang', 'ASC')->get();

        // 5 Barang dengan pemakaian terbanyak (Top Moving Items)
        $topMoving = DB::table('barang_keluar')
            ->join('master_barang', 'barang_keluar.barang_id', '=', 'master_barang.idbarang')
            ->select('master_barang.idbarang', 'master_barang.kodebarang', 'master_barang.namabarang', 'master_barang.stok_gudang', DB::raw('SUM(barang_keluar.jumlah) as total_keluar'))
            ->groupBy('master_barang.idbarang', 'master_barang.kodebarang', 'master_barang.namabarang', 'master_barang.stok_gudang')
            ->orderByDesc('total_keluar')
            ->limit(5)
            ->get();

        return view('prediksi.index', compact('barangList', 'allBarang', 'topMoving', 'search', 'kodeKategori'));
    }

    /**
     * Detail Perhitungan Weighted Moving Average (WMA) & Visualisasi Grafik
     */
    public function prediksi(Request $request, $idbarang)
    {
        $barang = Barang::findOrFail($idbarang);
        $allBarang = Barang::orderBy('namabarang', 'ASC')->get();

        // Jumlah periode bobot (default 3 bulan)
        $bobotAwal = (int) $request->input('bobot_awal', 3);
        if ($bobotAwal < 1) {
            $bobotAwal = 1;
        } elseif ($bobotAwal > 12) {
            $bobotAwal = 12;
        }

        // Ambil riwayat pemakaian barang per bulan dari tabel barang_keluar
        $riwayatPemakaian = DB::table('barang_keluar')
            ->selectRaw('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun, SUM(jumlah) as total_keluar')
            ->where('barang_id', $idbarang)
            ->whereNotNull('tanggal')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->limit($bobotAwal)
            ->get();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $detailPerhitungan = [];
        $totalBobot = 0;
        $totalNilaiBobot = 0;
        $totalPrediksi = 0;

        $jumlahData = $riwayatPemakaian->count();

        if ($jumlahData > 0) {
            // Skema pembobotan: Periode paling baru mendapat bobot tertinggi
            // Contoh jika ada 3 periode: bobot berturut-turut adalah 3, 2, 1
            $currentBobot = $jumlahData;

            foreach ($riwayatPemakaian as $item) {
                $bobotItem = $currentBobot;
                $nilaiBobot = $bobotItem * $item->total_keluar;

                $totalBobot += $bobotItem;
                $totalNilaiBobot += $nilaiBobot;

                $labelBulanTahun = ($namaBulan[$item->bulan] ?? 'Bln ' . $item->bulan) . ' ' . $item->tahun;

                $detailPerhitungan[] = [
                    'bulan' => $item->bulan,
                    'tahun' => $item->tahun,
                    'label' => $labelBulanTahun,
                    'pemakaian' => (int) $item->total_keluar,
                    'bobot' => $bobotItem,
                    'hasil_kali' => $nilaiBobot,
                ];

                $currentBobot--;
            }

            $totalPrediksi = $totalBobot > 0 ? ($totalNilaiBobot / $totalBobot) : 0;
        }

        // Siapkan data untuk Chart.js (diurutkan kronologis: lama -> baru)
        $chartKronologis = array_reverse($detailPerhitungan);
        $chartLabels = array_column($chartKronologis, 'label');
        $chartValues = array_column($chartKronologis, 'pemakaian');

        // Analisis status persediaan
        $stokSaatIni = (int) $barang->stok_gudang;
        $estimasiKebutuhan = (int) ceil($totalPrediksi);
        $statusStok = 'Aman';
        $saranPengadaan = 0;

        if ($stokSaatIni < $estimasiKebutuhan) {
            $statusStok = 'Perlu Pengadaan';
            $saranPengadaan = $estimasiKebutuhan - $stokSaatIni;
        }

        return view('prediksi.detail', compact(
            'barang',
            'allBarang',
            'bobotAwal',
            'jumlahData',
            'detailPerhitungan',
            'totalBobot',
            'totalNilaiBobot',
            'totalPrediksi',
            'chartLabels',
            'chartValues',
            'stokSaatIni',
            'estimasiKebutuhan',
            'statusStok',
            'saranPengadaan'
        ));
    }
}
