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
            <h3>Penerimaan Barang</h3>
        </div>

        <section class="section">
            <div class="card">

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="GET" action="{{ route('po.masuk') }}">
                        <div class="form-group">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" required>

                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" required>

                        </div>
                        <div class="form-group">
                            <label for="status">Status PO Barang:</label>
                            <select id="status" name="status" required>
                                <option value="sudah_diterima" {{ request('status') == 'sudah_diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                                <option value="belum_diterima" {{ request('status') == 'belum_diterima' ? 'selected' : '' }}>Belum</option>
                            </select>
                        </div>
                        <button type="submit">Tampilkan</button>
                    </form>
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No PO</th>
                                <th>Nama Suplier</th>
                                <th>Expired Kirim</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Preorder as $db)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $db->tanggal_po }}</td> <!-- Menampilkan nomor urut -->
                                <td>{{ $db->nomor_po }}</td>
                                <td>{{ $db->suplier }}</td>
                                <td>{{ $db->expired_po }}</td>
                                <td>
                                    <!-- Button Untuk Tambah Barang -->
                                    <div class="form-group">
                                        <button type="button" class="btn btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#tambahbarang{{ $db->id_po }}">
                                            Masuk
                                        </button>

                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="tambahbarang{{ $db->id_po }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="tambahBarangLabel{{ $db->id_po }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="tambahBarangLabel{{ $db->id_po }}">
                                                        Tambah Barang: {{ $db->nama_barang }}
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('po.acc') }}" method="POST">
                                                    @csrf
                                                    <!-- Hidden Input untuk Barang ID -->
                                                    <input type="hidden" name="po_id"
                                                        value="{{ $db->id_po }}">
                                                    <input type="hidden" name="barang_id"
                                                        value="{{ $db->barang_id }}">
                                                    <input type="hidden" name="user_logistik" 
                                                        value="{{ auth()->user()->username }}">

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="kode-barang">Nama Barang</label>
                                                            <input type="text" class="form-control"
                                                                id="kode-barang"
                                                                placeholder="{{ $db->gudang->namabarang }}"
                                                                readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="kode-barang">Jumlah Stok</label>
                                                            <input type="text" class="form-control"
                                                                id="kode-barang"
                                                                placeholder="{{ $db->jumlah_po }}"
                                                                readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="tanggal"
                                                                class="form-label">Tanggal</label>
                                                            <input type="date" name="tanggal"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="masuk"
                                                                class="form-label">Barang Masuk</label>
                                                            <input type="number" name="masuk"
                                                                class="form-control" min="1"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit"
                                                            class="btn btn-primary">Masuk</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                </div>



                </tr>
                @endforeach
                </tbody>
                </table>
            </div>
        </section>
        @include('component-web.footer')
    </div>
    @include('component-web.script')
</body>

</html>