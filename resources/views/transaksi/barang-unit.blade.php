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
                <h3>Tambah Barang Anfra</h3>
            </div>


                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            Tambah Barang Anfra
                        </div>
                        <div class="card-body">

                                <form action="{{ route('anfra.barang') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="kode-barang">Kode Barang</label>
                                        <input type="text" class="form-control" id="kode-barang" placeholder="Kode Barang" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="input-datalist">Nama Barang</label>
                                        <input type="hidden" name="barang_id" id="barang-id">
                                        <input type="text" class="form-control" placeholder="Nama Barang" id="input-datalist">
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', () =>
                                        {
                                            const barangList = @json($Barang->map(fn($item) =>
                                            [
                                                'id' => $item->idbarang,
                                                'namabarang' => $item->namabarang,
                                                'kodebarang' => $item->kodebarang
                                            ]));

                                            const barangNames = barangList.map(item => item.namabarang);

                                            $('#input-datalist').autocomplete({
                                                source: barangNames,
                                                select: function(event, ui) {
                                                    const selectedBarang = barangList.find(item => item.namabarang === ui.item.value);
                                                    if (selectedBarang) {
                                                        $('#barang-id').val(selectedBarang.id);
                                                        $('#kode-barang').val(selectedBarang.kodebarang);
                                                    }
                                                }
                                            });
                                        });
                                    </script>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>

                            <!-- Modal Bootstrap -->
                            <div class="modal text-left" id="errorModal" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel6" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel6">Peringatan</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">

                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $errors->first('barang_id') }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-secondary"
                                                data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Close</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ml-1" data-bs-dismiss="modal">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Accept</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($errors->has('barang_id'))
                                <script>
                                    document.addEventListener("DOMContentLoaded", function()
                                    {
                                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                                        errorModal.show();
                                    });
                                </script>
                            @endif
                        </div>
                    </div>

                </section>

            @include('component-web.footer')
        </div>
        @include('component-web.script')
</body>

</html>
