<!DOCTYPE html>
<html>

<head>
    <title>Laporan Rekapitulasi Anggaran Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
        }

        h2 {
            text-align: center;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #333;
        }

        th, td {
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .tfoot-total {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .signature-table {
            border: none;
            width: 100%;
            margin-top: 40px;
        }

        .signature-table td {
            border: none;
            padding: 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>RUMAH SAKIT ISLAM SJS</h2>
    <h2>LAPORAN REKAPITULASI ANGGARAN PEMBELIAN BARANG</h2>
    <p class="subtitle">
        Periode: <strong>{{ $namaBulan[$month] ?? $month }} {{ $year }}</strong>
        @if($kodeInput) | Kategori: <strong>{{ $kodeInput }}</strong> @endif
    </p>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 70px;">Kode Barang</th>
                <th>Nama Barang</th>
                <th style="width: 50px;">Satuan</th>
                <th style="width: 80px;">Harga Satuan</th>
                <th style="width: 45px;">QTY</th>
                <th style="width: 95px;">Total Biaya</th>
                <th>Supplier Acuan</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggaranList as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td class="text-center">{{ $item->kodebarang }}</td>
                <td>{{ $item->namabarang }}</td>
                <td class="text-center">{{ $item->idsatuan }}</td>
                <td class="text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($item->jumlah) }}</td>
                <td class="text-end">Rp {{ number_format($item->total_biaya, 0, ',', '.') }}</td>
                <td>{{ $item->suplier_terakhir }}</td>
                <td>{{ $item->catatan ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data perencanaan anggaran pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($anggaranList->count() > 0)
        <tfoot>
            <tr class="tfoot-total">
                <td colspan="6" class="text-end">TOTAL ANGGARAN:</td>
                <td class="text-center">{{ number_format($anggaranList->sum('jumlah')) }}</td>
                <td class="text-end">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                <br>
                Petugas Pengadaan / Logistik,
                <br><br><br><br>
                ( ______________________ )
            </td>
            <td style="width: 50%;">
                Dicetak pada: {{ date('d-m-Y H:i') }}<br>
                Mengetahui,<br>
                Kepala Bagian Keuangan / Direktur,
                <br><br><br><br>
                ( ______________________ )
            </td>
        </tr>
    </table>
</body>

</html>
