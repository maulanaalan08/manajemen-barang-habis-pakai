<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pre Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        h2 {
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: right;
        }
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
                <th>Nomor PO</th>
                <th>Jumlah PO</th>
                <th>Harga</th>
                <th>Suplier</th>
                <th>Tanggal PO</th>
                <th>Expired PO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Preorder as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->gudang?->kodebarang ?? '-' }}</td>
                <td>{{ $item->gudang?->namabarang ?? '-' }}</td>
                <td>{{ $item->nomor_po }}</td>
                <td>{{ $item->jumlah_po }}</td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td>{{ $item->suplier }}</td>
                <td>{{ $item->tanggal_po }}</td>
                <td>{{ $item->expired_po }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }} <br>
        Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>