<!DOCTYPE html>
<html lang="en">

<body>
    @include('component-web.sidebar')
    @include('component-web.head')

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>List PO Barang</h3>
            </div>

                <section class="section">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Nomer PO</th>
                                        <th>Jumlah PO</th>
                                        <th>Nama Suplier</th>
                                        <th>Tanggal Expired</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Preorder as $db)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $db->gudang->kodebarang}}</td>
                                            <td>{{ $db->gudang->namabarang }}</td>
                                            <td>{{ $db->nomor_po }}</td>
                                            <td>{{ $db->jumlah_po }}</td>
                                            <td>{{ $db->suplier }}</td>
                                            <td>{{ $db->expired_po }}</td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                            <a href="{{ route('po.barang') }}" class="btn btn-primary">Tambah</a>
                            
                        </div>
                    </div>

                </section>
                @include('component-web.footer')
        </div>
        @include('component-web.script')


</body>

</html>
