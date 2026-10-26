<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pengajuan Barang Unit {{auth()->user()->name}}</title>
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
    <h2>Laporan Pengajuan Barang Unit {{auth()->user()->name}}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jumlah Diminta</th>
                <th>Tanggal</th>
                <th>Pemohon</th>
                <th>Unit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $data->unitStok->kodebarang }}</td>
                <td>{{ $data->unitStok->namabarang }}</td>
                <td>{{ $data->masuk }}</td>
                <td>{{ $data->tanggal }}</td>
                <td>{{ $data->nama_user }}</td>
                <td>{{ $data->user_unit }}</td>
                <td>
                    @if($data->status == 'pending')
                        <span style="color: red;">Pending</span>
                    @else($data->status == 'approved')
                        <span style="color: green;">Approved</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}<br>
        Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>
</body>
</html>
 