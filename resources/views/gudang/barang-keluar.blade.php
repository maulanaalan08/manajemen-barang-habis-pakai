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
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Permintaan Barang</h3>
                    </div>
                </div>
            </div>
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <form method="GET" action="{{ route('aproval.barang') }}">
                            <div class="form-group">
                                <label for="status">Status Permintaan Barang:</label>
                                <select id="status" name="status" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="belum_diterima" {{ request('status') == 'belum_diterima' ? 'selected' : '' }}>Belum</option>
                                    <option value="sudah_diterima" {{ request('status') == 'sudah_diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Sisa Stok</th>
                                    <th>Nama Peminta</th>
                                    <th>Unit Peminta</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Barang as $db)
                                <tr>
                                    <td>{{ $loop->iteration }}</td> <!-- Menampilkan nomor urut -->
                                    <td>{{ $db->tanggal }}</td>
                                    <td>{{ $db->unitStok->namabarang }}</td>
                                    <td>{{ $db->masuk }}</td>
                                    <td>{{ $db->unitStok->stok_gudang }}</td>
                                    <td>{{ $db->nama_user }}</td>
                                    <td>{{ $db->user_unit }}</td>
                                    <td>{{ $db->status }}</td>
                                    <td>
                                        <!-- Button Untuk Tambah Barang -->
                                        <div class="form-group">
                                            <button type="button" class="btn btn-outline-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#tambahbarang{{ $db->id_transaksi_unit }}">
                                                Masuk
                                            </button>
                                            <a href="{{ route('transaksi.logistik', ['id' => $db->id_transaksi_unit]) }}"
                                                target="_blank"
                                                class="btn btn-outline-primary">
                                                Cetak
                                            </a>

                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="tambahbarang{{ $db->id_transaksi_unit }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="tambahBarangLabel{{ $db->id_transaksi_unit }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="tambahBarangLabel{{ $db->id_transaksi_unit }}">
                                                            Tambah Barang: {{ $db->unitStok->namabarang }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('barang.acc', $db->id_transaksi_unit) }}" method="POST">
                                                        @csrf

                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="stok-gudang">Jumlah Stok</label>
                                                                <input type="text" class="form-control" value="{{ $db->unitStok->stok_gudang }}" readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="total-keluar">Jumlah Permintaan</label>
                                                                <input type="text" class="form-control" value="{{ $db->masuk }}" readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="jumlah-disetujui" class="form-label">Jumlah Disetujui</label>
                                                                <input type="number" name="jumlah_disetujui" class="form-control" min="1" max="{{ $db->masuk }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Setujui</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let today = new Date();
                let year = today.getFullYear();
                let month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan harus 2 digit
                let day = String(today.getDate()).padStart(2, '0'); // Hari harus 2 digit

                let formattedDate = `${year}-${month}-${day}`;

                // Setel semua input hidden dengan class "tanggal-input"
                document.querySelectorAll(".tanggal-input").forEach(input => {
                    input.value = formattedDate;
                });
            });
        </script>

        @include('component-web.footer')
    </div>
    @include('component-web.script')

</body>

</html>