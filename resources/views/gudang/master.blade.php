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
            <h3>Master Barang</h3>
        </div>

        <section class="section">
            <div class="card">

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                    @endif
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok Barang</th>
                                <th>Satuan</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Barang as $db)
                            <tr>
                                <td>{{ $loop->iteration }}</td> <!-- Menampilkan nomor urut -->
                                <td>{{ $db->kodebarang }}</td>
                                <td>{{ $db->namabarang }}</td>
                                <td>{{ $db->stok_gudang }}</td>
                                <td>{{ $db->idsatuan }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @auth
                    @if (auth()->user()->hasRole('admin'))
                    <a href="/tambahBarang" class="btn btn-primary">Tambah</a>
                    @endif
                    @endauth
                </div>
            </div>
        </section>

        @include('component-web.footer')

    </div>

    @include('component-web.script')

</body>

</html>