<!DOCTYPE html>
<html lang="id">

@include('component-web.head', ['title' => 'Rekapitulasi Anggaran Pembelian'])

<body>
    <div id="app">
        @include('component-web.sidebar')

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title mb-3">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-7">
                            <h3><i class="bi bi-receipt text-primary me-2"></i>Rekapitulasi Anggaran Pembelian</h3>
                            <p class="text-subtitle text-muted">Laporan rekapitulasi rencana pengadaan dan total estimasi pembiayaan barang habis pakai.</p>
                        </div>
                        <div class="col-12 col-md-5 text-md-end">
                            <a href="{{ route('anggaran') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-plus-circle me-1"></i> Buat Anggaran Baru
                            </a>
                            <a href="{{ route('anggaran.cetak', ['month' => $month, 'year' => $year, 'kode' => $kodeInput]) }}" target="_blank" class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 3 Ringkasan KPI Card --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-primary text-white h-100">
                            <div class="card-body">
                                <h6 class="text-white-50 text-uppercase small mb-1">Total Estimasi Anggaran ({{ $namaBulan[$month] ?? $month }} {{ $year }})</h6>
                                <h3 class="text-white fw-bold mb-0">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h3>
                                <small class="text-white-50">Akumulasi biaya seluruh item</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted text-uppercase small mb-1">Total Kuantitas Barang</h6>
                                <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalKuantitas) }}</h3>
                                <small class="text-muted">Total unit barang yang direncanakan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-muted text-uppercase small mb-1">Jumlah Item Barang</h6>
                                <h3 class="fw-bold mb-0 text-success">{{ $anggaranList->count() }} Jenis Item</h3>
                                <small class="text-muted">Item barang terdaftar dalam rencana anggaran</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filter Periode & Kategori --}}
                <section class="section">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-transparent py-3">
                            <form method="GET" action="{{ route('anggaran.pembelian') }}" class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Pilih Bulan:</label>
                                    <select id="month" name="month" class="form-select">
                                        @foreach($namaBulan as $mKey => $mName)
                                        <option value="{{ $mKey }}" {{ $month == $mKey ? 'selected' : '' }}>
                                            {{ $mName }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Pilih Tahun:</label>
                                    <select id="year" name="year" class="form-select">
                                        @for ($y = date('Y') - 3; $y <= date('Y') + 2; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Kategori Barang:</label>
                                    <select id="kode" name="kode" class="form-select">
                                        <option value="">-- Semua Kategori --</option>
                                        <option value="AIR" {{ request('kode') == 'AIR' ? 'selected' : '' }}>AIR - Air Minum / Galon</option>
                                        <option value="ATK" {{ request('kode') == 'ATK' ? 'selected' : '' }}>ATK - Alat Tulis Kantor</option>
                                        <option value="ART" {{ request('kode') == 'ART' ? 'selected' : '' }}>ART - Alat Rumah Tangga</option>
                                        <option value="CLS" {{ request('kode') == 'CLS' ? 'selected' : '' }}>CLS - Bahan Kimia / Kebersihan</option>
                                        <option value="BLH" {{ request('kode') == 'BLH' ? 'selected' : '' }}>BLH - Bangunan Lingkungan Hidup</option>
                                        <option value="LIN" {{ request('kode') == 'LIN' ? 'selected' : '' }}>LIN - Linen</option>
                                        <option value="PCK" {{ request('kode') == 'PCK' ? 'selected' : '' }}>PCK - Percetakan</option>
                                        <option value="MKN" {{ request('kode') == 'MKN' ? 'selected' : '' }}>MKN - Makanan Kering</option>
                                        <option value="MEC" {{ request('kode') == 'MEC' ? 'selected' : '' }}>MEC - Mechanical Electrical</option>
                                        <option value="GAS" {{ request('kode') == 'GAS' ? 'selected' : '' }}>GAS - Gas Medis / LPG</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-filter"></i> Tampilkan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle" id="table1">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Tanggal</th>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th class="text-end">Harga Satuan</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-end">Total Biaya</th>
                                            <th>Supplier Acuan</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($anggaranList as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                            <td><span class="badge bg-secondary font-monospace">{{ $item->kodebarang }}</span></td>
                                            <td class="fw-semibold">{{ $item->namabarang }}</td>
                                            <td>{{ $item->idsatuan }}</td>
                                            <td class="text-end font-monospace">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td class="text-center fw-bold">{{ number_format($item->jumlah) }}</td>
                                            <td class="text-end font-monospace fw-bold text-primary">
                                                Rp {{ number_format($item->total_biaya, 0, ',', '.') }}
                                            </td>
                                            <td class="small text-muted">{{ $item->suplier_terakhir }}</td>
                                            <td class="small">{{ $item->catatan ?: '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-5">
                                                <i class="bi bi-calendar-x fs-1 d-block mb-2 text-warning"></i>
                                                Belum ada data perencanaan anggaran pada periode <strong>{{ $namaBulan[$month] ?? $month }} {{ $year }}</strong>.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    @if($anggaranList->count() > 0)
                                    <tfoot class="table-light">
                                        <tr class="fw-bold fs-6">
                                            <td colspan="6" class="text-uppercase text-end">Total Seluruh Anggaran:</td>
                                            <td class="text-center text-dark">{{ number_format($totalKuantitas) }}</td>
                                            <td class="text-end text-primary font-monospace">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('component-web.footer')
        </div>
    </div>

    @include('component-web.script')
    <script>
        $(document).ready(function() {
            $('#table1').DataTable();
        });
    </script>
</body>

</html>
