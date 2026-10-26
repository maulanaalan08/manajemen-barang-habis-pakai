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
            <h3>Laporan Barang Masuk</h3>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    Laporan Barang Masuk
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.masuk') }}">
                        <div class="form-group">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" required>

                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" required>

                        </div>
                        <div class="form-group">
                            <label for="kode">Pilih Kategori Barang:</label>
                            <select id="kode" name="kode" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value=" " {{ request('kode') == '' ? 'selected' : '' }}>Semua Kategori</option>
                                <option value="AIR" {{ request('kode') == 'AIR' ? 'selected' : '' }}>AIR</option>
                                <option value="ATK" {{ request('kode') == 'ATK' ? 'selected' : '' }}>Alat Tulis Kantor</option>
                                <option value="ART" {{ request('kode') == 'ART' ? 'selected' : '' }}>ALAT RUMAH TANGGA</option>
                                <option value="CLS" {{ request('kode') == 'CLS' ? 'selected' : '' }}>Bahan Kimia</option>
                                <option value="BLH" {{ request('kode') == 'BLH' ? 'selected' : '' }}>BANGUNAN LINGKUNGAN HIDUP</option>
                                <option value="LIN" {{ request('kode') == 'LIN' ? 'selected' : '' }}>LINEN</option>
                                <option value="PCK" {{ request('kode') == 'PCK' ? 'selected' : '' }}>PERCETAKAN</option>
                                <option value="MKN" {{ request('kode') == 'MKN' ? 'selected' : '' }}>MAKANAN KERING</option>
                                <option value="MEC" {{ request('kode') == 'MEC' ? 'selected' : '' }}>MECHANICAL ELECTRICAL</option>
                                <option value="GAS" {{ request('kode') == 'GAS' ? 'selected' : '' }}>GAS</option>
                            </select>

                        </div>

                        <button type="submit">Tampilkan</button>

                        @if(request('start_date') && request('end_date'))
                        <a href="{{ route('cetak.laporan', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'kode' => request('kode')]) }}"
                            target="_blank" style="margin-left: 10px;">
                            <button type="button">Cetak PDF</button>
                        </a>
                        @endif
                    </form>
                    @if(isset($Barang) && $Barang->count() > 0)
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Masuk</th>
                                <th>Suplier</th>
                                <th>Tanggal Diterima</th>
                                <th>Nama Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Barang as $db)
                            <tr>
                                <td>{{ $loop->iteration }}</td> <!-- Menampilkan nomor urut -->
                                <td>{{ $db->kodebarang }}</td>
                                <td>{{ $db->namabarang }}</td>
                                <td>{{ $db->masuk }}</td>
                                <td>{{ $db->suplier }}</td>
                                <td>{{ $db->tanggal }}</td>
                                <td>{{ $db->user_logistik }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>Data tidak ditemukan atau belum dipilih.</p>
                    @endif
                </div>
            </div>

        </section>
        @include('component-web.footer')

    </div>
    @include('component-web.script')

</body>

</html>