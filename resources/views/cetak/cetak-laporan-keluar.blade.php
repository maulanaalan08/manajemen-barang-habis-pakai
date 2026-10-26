<!DOCTYPE html>
<html>

<head>
    <title>Laporan Barang Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 8px 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 50px;
            font-size: 11px;
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>LAPORAN BARANG KELUAR</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>User Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($Barang as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kodebarang }}</td>
                <td>{{ $item->namabarang }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->user_logistik }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}<br>
        Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>

</html>