<!DOCTYPE html>
<html lang="en">



<body>
    @include('component-web.head')
    @include('component-web.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>Purchase Order</h3>
            </div>


                <section class="section">
                    <div class="card">
                        <div class="card-body">

                            <div class="container">
                                <h1>Purchase Order</h1>

                                <form action="{{ route('po.tambah') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="nomor-po">Nomor PO</label>
                                        <input type="text" class="form-control" name="nomor_po" id="nomor-po" readonly required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal_po">Tanggal PO</label>
                                        <input type="date" class="form-control" name="tanggal_po" id="tanggal_po" readonly required>
                                    </div>

                                    <div class="form-group">
                                        <label for="suplier">Nama Suplier</label>
                                        <input type="text" class="form-control" name="suplier" id="suplier" placeholder="Nama Suplier" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="tanggal">Tanggal Expired PO</label>
                                        <input type="date" class="form-control" name="expired_po" id="expired_po" required>
                                    </div>

                                    <div id="po-items">
                                        <div class="po-item">
                                            <div class="form-group">
                                                <label for="kode-barang">Kode Barang</label>
                                                <input type="text" class="form-control kode-barang" placeholder="Kode Barang" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="input-datalist">Nama Barang</label>
                                                <input type="hidden" name="barang_id[]" class="barang-id">
                                                <input type="text" class="form-control input-datalist" placeholder="Nama Barang">
                                            </div>

                                            <div class="form-group">
                                                <label for="jumlah_po">Jumlah PO</label>
                                                <input type="number" class="form-control jumlah_po" name="jumlah_po[]" placeholder="Masukkan Jumlah" min="1" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="harga">Harga</label>
                                                <input type="number" class="form-control harga" name="harga[]" placeholder="Masukkan Harga" min="1" required>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="total-harga">Total Harga</label>
                                        <input type="text" class="form-control" name="total_harga" id="total-harga" readonly>
                                    </div>

                                    <button type="button" id="add-item" class="btn btn-success">Tambah Barang</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>

                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const poItems = document.getElementById('po-items');
                                        const addItemButton = document.getElementById('add-item');
                                        const totalHargaInput = document.getElementById('total-harga');

                                        const barangList = @json(collect($Barang)->map(fn($item) => [
                                            'id' => $item->idbarang,
                                            'namabarang' => $item->namabarang,
                                            'kodebarang' => $item->kodebarang
                                        ]));

                                        const barangNames = barangList.map(item => item.namabarang);

                                        function enableAutocomplete(inputElement) {
                                            $(inputElement).autocomplete({
                                                source: barangNames,
                                                select: function(event, ui) {
                                                    const selectedBarang = barangList.find(item => item.namabarang === ui.item.value);
                                                    if (selectedBarang) {
                                                        $(inputElement).closest('.po-item').find('.barang-id').val(selectedBarang.id);
                                                        $(inputElement).closest('.po-item').find('.kode-barang').val(selectedBarang.kodebarang);
                                                    }
                                                }
                                            });
                                        }

                                        function updateTotalHarga() {
                                            let total = 0;
                                            document.querySelectorAll('.po-item').forEach(item => {
                                                const jumlah = parseInt(item.querySelector('.jumlah_po').value) || 0;
                                                const harga = parseFloat(item.querySelector('.harga').value) || 0;
                                                total += jumlah * harga;
                                            });
                                            totalHargaInput.value = total.toFixed(2);
                                        }

                                        // Event tambah barang
                                        addItemButton.addEventListener('click', () => {
                                            const newItem = document.querySelector('.po-item').cloneNode(true);
                                            newItem.querySelectorAll('input').forEach(input => input.value = '');

                                            // Pastikan tombol hapus baru bisa digunakan
                                            newItem.querySelector('.remove-item').addEventListener('click', function () {
                                                newItem.remove();
                                                updateTotalHarga();
                                            });

                                            // Aktifkan autocomplete pada input baru
                                            enableAutocomplete(newItem.querySelector('.input-datalist'));

                                            // Update total harga ketika jumlah atau harga diubah
                                            newItem.querySelector('.jumlah_po').addEventListener('input', updateTotalHarga);
                                            newItem.querySelector('.harga').addEventListener('input', updateTotalHarga);

                                            poItems.appendChild(newItem);
                                        });

                                        // Event hapus barang (gunakan event delegation)
                                        poItems.addEventListener('click', function (event) {
                                            if (event.target.classList.contains('remove-item')) {
                                                if (document.querySelectorAll('.po-item').length > 1) {
                                                    event.target.closest('.po-item').remove();
                                                    updateTotalHarga();
                                                }
                                            }
                                        });

                                        // Aktifkan autocomplete pada input awal
                                        document.querySelectorAll('.input-datalist').forEach(enableAutocomplete);

                                        // Event listener untuk update total harga saat input jumlah/harga berubah
                                        document.querySelectorAll('.jumlah_po, .harga').forEach(input => {
                                            input.addEventListener('input', updateTotalHarga);
                                        });

                                    });
                                </script>


                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const barangList = @json(collect($Barang)->map(fn($item) => [
                                            'id' => $item->idbarang,
                                            'namabarang' => $item->namabarang,
                                            'kodebarang' => $item->kodebarang
                                        ]));

                                        const barangNames = barangList.map(item => item.namabarang);
                                    });
                                </script>


                            </div>

                            <!-- Modal Bootstrap -->
                            <div class="modal text-left" id="errorModal" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel6" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel6">Peringatan</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $errors->first('gudang_id') }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
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



                                    {{-- tanggal --}}
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            let today = new Date();
                                            let year = today.getFullYear();
                                            let month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan harus 2 digit
                                            let day = String(today.getDate()).padStart(2, '0'); // Hari harus 2 digit

                                            let formattedDate = `${year}-${month}-${day}`;
                                            document.getElementById("tanggal_po").value = formattedDate;
                                        });
                                    </script>

                                    {{-- harga --}}
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const jumlahPoInput = document.getElementById("jumlah_po");
                                            const hargaInput = document.getElementById("harga");
                                            const totalInput = document.getElementById("total");

                                            function calculateTotal() {
                                                const jumlahPo = parseInt(jumlahPoInput.value) || 0; // Menghindari NaN jika input kosong
                                                const harga = parseFloat(hargaInput.value) || 0; // Menghindari NaN jika input kosong

                                                const total = jumlahPo * harga;
                                                totalInput.value = total.toFixed(2); // Format ke dua angka desimal
                                            }

                                            // Event listener untuk menghitung total saat perubahan pada input jumlah PO atau harga
                                            jumlahPoInput.addEventListener("input", calculateTotal);
                                            hargaInput.addEventListener("input", calculateTotal);
                                        });
                                    </script>


                                    {{-- jatuh tempo --}}
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            let today = new Date();
                                            today.setDate(today.getDate() + 14);
                                            let year = today.getFullYear();
                                            let month = String(today.getMonth() + 1).padStart(2, '0'); // Bulan harus 2 digit
                                            let day = String(today.getDate()).padStart(2, '0'); // Hari harus 2 digit

                                            let formattedDate = `${year}-${month}-${day}`;
                                            document.getElementById("expired_po").value = formattedDate;
                                        });
                                    </script>

                                    {{-- Nomer po --}}
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            function generateNomorPO() {
                                                let today = new Date();
                                                let tahun = today.getFullYear();
                                                let bulan = today.getMonth() + 1;
                                                let bulanRomawi = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
                                                let bulanFix = bulanRomawi[bulan - 1];

                                                // Ambil bulan terakhir dari localStorage
                                                let lastMonth = localStorage.getItem("last_month");
                                                let lastPO = localStorage.getItem("last_po");

                                                // Jika bulan berubah, reset nomor PO ke 001
                                                if (lastMonth !== `${tahun}-${bulan}`) {
                                                    localStorage.setItem("last_po", "000");
                                                    localStorage.setItem("last_month", `${tahun}-${bulan}`);
                                                    lastPO = "000"; // Reset ke 000 agar nanti jadi 001
                                                }

                                                // Generate nomor PO baru
                                                let nextPO = String(parseInt(lastPO) + 1).padStart(3, '0');

                                                // Simpan nomor terbaru ke localStorage
                                                localStorage.setItem("last_po", nextPO);

                                                return `PO/${tahun}/${bulan}/${nextPO}`;
                                            }

                                            // Set nomor PO ke input
                                            document.getElementById("nomor-po").value = generateNomorPO();
                                        });
                                    </script>

                                    {{-- autocomplete --}}
                                    {{-- <script>
                                        document.addEventListener('DOMContentLoaded', () => {
                                            const barangList = @json(collect($Barang)->map(fn($item) => [
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
                                    </script> --}}

                                    <script>
                                        document.addEventListener('DOMContentLoaded', () => {
                                            const barangList = @json(collect($Barang)->map(fn($item) => [
                                                'id' => $item->idbarang,
                                                'namabarang' => $item->namabarang,
                                                'kodebarang' => $item->kodebarang
                                            ]));

                                            const barangNames = barangList.map(item => item.namabarang);

                                            function enableAutocomplete(inputElement) {
                                                $(inputElement).autocomplete({
                                                    source: barangNames,
                                                    select: function(event, ui) {
                                                        const selectedBarang = barangList.find(item => item.namabarang === ui.item.value);
                                                        if (selectedBarang) {
                                                            $(inputElement).closest('.po-item').find('.barang-id').val(selectedBarang.id);
                                                            $(inputElement).closest('.po-item').find('.kode-barang').val(selectedBarang.kodebarang);
                                                        }
                                                    }
                                                });
                                            }

                                            // Aktifkan autocomplete untuk input yang sudah ada
                                            document.querySelectorAll('.input-datalist').forEach(enableAutocomplete);

                                            // Event untuk menambahkan input baru
                                            document.getElementById('add-item').addEventListener('click', () => {
                                                const newItem = document.querySelector('.po-item').cloneNode(true);
                                                newItem.querySelectorAll('input').forEach(input => input.value = '');
                                                document.getElementById('po-items').appendChild(newItem);

                                                // Aktifkan autocomplete pada input baru
                                                enableAutocomplete(newItem.querySelector('.input-datalist'));
                                            });

                                        });
                                    </script>


                            @if ($errors->has('gudang_id'))
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


            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2021 &copy; Mazer</p>
                    </div>
                    <div class="float-end">
                        <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a
                                href="https://saugi.me">Saugi</a></p>
                    </div>
                </div>
            </footer>
        </div>
    @include('component-web.script')




</body>

</html>
