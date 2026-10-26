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
            <h3>ANFRA Barang</h3>
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

                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($BarangUnit as $db)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $db->barangUnit->kodebarang}}</td>
                                    <td>{{ $db->barangUnit->namabarang }}</td>
                                    <td>{{ $db->barangUnit->stok_gudang }}</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#tambahbarang{{ $db->barang_id }}">
                                                Masuk
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <a href="{{ route('anfra.tambah') }}" class="btn btn-primary">Tambah</a>
                    <a href="/proses/transaksi" class="btn btn-primary">Lihat Proses</a>
                    
                </div>
            </div>

            {{-- Modals diletakkan di luar tabel agar DOM DataTables valid --}}
            @foreach ($BarangUnit as $db)
            <div class="modal fade" id="tambahbarang{{ $db->barang_id }}" tabindex="-1"
                role="dialog" aria-labelledby="tambahBarangLabel{{ $db->barang_id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahBarangLabel{{ $db->barang_id }}">
                                Tambah Barang: {{ $db->barangUnit->namabarang }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('anfra.stok') }}" method="POST">
                            @csrf
                            <input type="hidden" name="barang_unit_id" value="{{ $db->id_barang_unit }}">
                            <input type="hidden" name="barang_id" value="{{ $db->barang_id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" name="masuk" class="form-control" min="1" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Modal Bootstrap untuk Error -->
            <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="errorModalLabel">Kesalahan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="errorMessage"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        @include('component-web.footer')
    </div>
    @include('component-web.script')

    <!-- Script untuk Menampilkan Modal Jika Ada Error -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var errorMessage = "{{ session('error') }}";
            if (errorMessage) {
                document.getElementById("errorMessage").textContent = errorMessage;
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            }
        });
    </script>
</body>

</html>
