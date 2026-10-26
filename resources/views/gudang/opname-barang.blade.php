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
            <h3>Opname Barang</h3>
        </div>

                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            Simple DataTable
                        </div>
                        <div class="card-body">

                            <div class="container">
                                <h1>Tambah Data Gizi</h1>

                                <form action="{{ route('opname.masuk') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="input-datalist">Nama Barang</label>
                                        <input type="hidden" name="barang_id" id="barang-id">
                                        <input type="text" class="form-control" placeholder="Nama Barang" id="input-datalist" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal">Tanggal</label>
                                        <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="stok-gudang">Stok Data</label>
                                        <input type="text" class="form-control" id="stok-gudang" placeholder="Stok Data" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="stok-opname">Stok Opname</label>
                                        <input type="number" class="form-control" name="stok_opname" id="stok-opname" placeholder="Stok Opname" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                                <script>
                                    document.addEventListener('DOMContentLoaded', () =>
                                    {
                                        const barangList = @json($Barang->map(fn($item) =>
                                        [
                                            'id' => $item->idbarang,
                                            'namabarang' => $item->namabarang,

                                            'stok_gudang' => $item->stok_gudang
                                        ]));

                                        const barangNames = barangList.map(item => item.namabarang);

                                        $('#input-datalist').autocomplete({
                                            source: barangNames,
                                            select: function(event, ui) {
                                                const selectedBarang = barangList.find(item => item.namabarang === ui.item.value);
                                                if (selectedBarang) {
                                                    $('#barang-id').val(selectedBarang.id);
                                                    $('#stok-gudang').val(selectedBarang.stok_gudang);
                                                }
                                            }
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                </section>

        @include('component-web.footer')

    </div>

    @include('component-web.script')

</body>

</html>