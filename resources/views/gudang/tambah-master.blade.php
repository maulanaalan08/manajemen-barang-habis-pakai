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
            <h3>Tambah Master Barang</h3>
        </div>
        <section class="section">
            <div class="card">

                <div class="card-body">

                    <div class="container">
                        <h1>Tambah Data Master</h1>

                        <form action="{{ route('barang.tambah') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="kodebarang">Kode Barang</label>
                                <input type="text" name="kodebarang" id="kodebarang" class="form-control" value="{{ old('kodebarang') }}" required>
                                @error('kodebarang')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="namabarang">Nama Barang</label>
                                <input type="text" name="namabarang" id="namabarang" class="form-control" value="{{ old('namabarang') }}" required>
                                @error('namabarang')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mt-3">
                                <label for="idsatuan">ID Satuan</label>
                                <input type="text" name="idsatuan" id="idsatuan" class="form-control" value="{{ old('idsatuan') }}" required>
                                @error('idsatuan')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Simpan</button>
                        </form>

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                    </div>

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
                        document.addEventListener("DOMContentLoaded", function() {
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