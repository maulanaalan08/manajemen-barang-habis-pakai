<!DOCTYPE html>
<html>
<head>
    <title>Laporan Barang Masuk</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; }
        .footer { margin-top: 40px; font-size: 11px; text-align: right; }
    </style>
</head>
<body>
    <h2>LAPORAN BARANG MASUK</h2>
    <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Suplier</th>
                <th>Tanggal</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($Barang as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kodebarang }}</td>
                    <td>{{ $item->namabarang }}</td>
                    <td>{{ $item->masuk }}</td>
                    <td>{{ $item->suplier }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->user_logistik }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }} <br>
        Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
