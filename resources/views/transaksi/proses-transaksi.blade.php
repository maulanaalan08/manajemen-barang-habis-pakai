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
            <h3>Proses Anfra Barang</h3>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <form method="GET" action="{{ route('proses.transaksi') }}">
                        <div class="form-group">
                            <label for="status">Status PO Barang:</label>
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
                                <th>Tanggal Anfra</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Sisa Stok</th>
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
                                <td>{{ $db->status }}</td>
                                <td>
                                    <!-- Button Untuk Tambah Barang -->
                                    <div class="form-group">
                                        <a href="{{ route('transaksi.cetak', ['id' => $db->id_transaksi_unit]) }}"
                                            target="_blank"
                                            class="btn btn-outline-primary">
                                            Cetak
                                        </a>
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

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