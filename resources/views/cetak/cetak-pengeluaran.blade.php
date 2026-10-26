<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengeluaran Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
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
    <h2>Laporan Pengeluaran Barang</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Diterima</th>
                <th>Tanggal</th>
                <!-- <th>Pemohon</th> -->
                <th>Petugas</th>
                <!-- <th>Unit</th> -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $data->barang->kodebarang }}</td>
                <td>{{ $data->barang->namabarang }}</td>
                <td>{{ $data->jumlah }}</td>
                <td>{{ $data->tanggal }}</td>
                <td>{{ $data->user_logistik }}</td>
                <!-- <td>{{ $data->nama_user }}</td> -->
                <!-- <td>{{ $data->user_unit }}</td> -->
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}<br>
        Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
 