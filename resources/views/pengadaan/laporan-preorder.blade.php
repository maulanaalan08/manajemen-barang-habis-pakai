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
            <h3>Laporan Pre Order</h3>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    Filter Laporan Pre Order
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.preorder') }}" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Dari Tanggal</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Sampai Tanggal</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
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
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                            @if(request('start_date') && request('end_date'))
                            <a href="{{ route('cetak.preorder', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'kode' => request('kode')]) }}"
                                target="_blank" style="margin-left: 10px;">
                                <button type="button">Cetak PDF</button>
                            </a>
                            @endif
                        </div>
                    </form>

                    @if ($Preorder->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Nomor PO</th>
                                    <th>Jumlah PO</th>
                                    <th>Harga</th>
                                    <th>Suplier</th>
                                    <th>Tanggal PO</th>
                                    <th>Expired PO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Preorder as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->gudang?->kodebarang ?? '-' }}</td>
                                    <td>{{ $item->gudang?->namabarang ?? '-' }}</td>
                                    <td>{{ $item->nomor_po }}</td>
                                    <td>{{ $item->jumlah_po }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->suplier }}</td>
                                    <td>{{ $item->tanggal_po }}</td>
                                    <td>{{ $item->expired_po }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted">Data pre order tidak ditemukan.</p>
                    @endif
                </div>
            </div>
        </section>

        @include('component-web.footer')
    </div>
    @include('component-web.script')
</body>

</html>