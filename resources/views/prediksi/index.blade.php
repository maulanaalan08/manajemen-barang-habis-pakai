<!DOCTYPE html>
<html lang="id">

@include('component-web.head', ['title' => 'Prediksi Kebutuhan Barang (WMA)'])

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
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3><i class="bi bi-graph-up-arrow text-primary"></i> Prediksi Kebutuhan Barang</h3>
                            <p class="text-subtitle text-muted">Peramalan kebutuhan persediaan barang habis pakai berbasis metode Weighted Moving Average (WMA).</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Prediksi WMA</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                {{-- Alert Info WMA --}}
                <div class="alert alert-light-primary alert-dismissible fade show border-primary" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill fs-3 me-3 text-primary"></i>
                        <div>
                            <strong>Metode Weighted Moving Average (WMA):</strong> Memberikan bobot lebih besar pada data pemakaian terbaru, sehingga hasil peramalan lebih responsif dan akurat terhadap tren konsumsi terkini.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                {{-- Top Moving Items --}}
                @if(isset($topMoving) && $topMoving->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent py-3">
                                <h5 class="card-title mb-0 text-dark"><i class="bi bi-fire text-danger me-2"></i>Barang Pemakaian Tertinggi (Rekomendasi Prediksi)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($topMoving as $top)
                                    <div class="col-md-4 col-lg">
                                        <div class="card border border-light-subtle h-100 shadow-none mb-0 hover-card">
                                            <div class="card-body p-3">
                                                <span class="badge bg-light-primary text-primary mb-2">{{ $top->kodebarang }}</span>
                                                <h6 class="text-truncate mb-1" title="{{ $top->namabarang }}">{{ $top->namabarang }}</h6>
                                                <div class="d-flex justify-content-between align-items-center mt-2 small text-muted">
                                                    <span>Stok: <strong>{{ $top->stok_gudang }}</strong></span>
                                                    <span>Keluar: <strong class="text-danger">{{ $top->total_keluar }}</strong></span>
                                                </div>
                                                <a href="{{ route('prediksi.detail', $top->idbarang) }}" class="btn btn-sm btn-outline-primary w-100 mt-3">
                                                    <i class="bi bi-calculator me-1"></i> Hitung Prediksi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Section Filter & Daftar Barang --}}
                <section class="section">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-transparent py-3">
                            <div class="row align-items-center g-3">
                                <div class="col-md-6">
                                    <h5 class="card-title mb-0">Pilih Barang untuk Dihitung</h5>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCepat">
                                        <i class="bi bi-lightning-charge me-1"></i> Prediksi Cepat via Dropdown
                                    </button>
                                </div>
                            </div>

                            {{-- Collapse Form Prediksi Cepat --}}
                            <div class="collapse mt-3" id="collapseCepat">
                                <div class="p-3 bg-light rounded-3 border">
                                    <form id="formCepat" onsubmit="event.preventDefault(); goToPrediksi();">
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Pilih Barang:</label>
                                                <select class="form-select" id="selectBarangCepat" required>
                                                    <option value="">-- Pilih Nama Barang --</option>
                                                    @foreach($allBarang as $b)
                                                    <option value="{{ $b->idbarang }}">{{ $b->kodebarang }} - {{ $b->namabarang }} (Stok: {{ $b->stok_gudang }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Jumlah Periode Bobot (Bulan):</label>
                                                <select class="form-select" id="selectBobotCepat">
                                                    <option value="3" selected>3 Periode (Bulan Terakhir - Standar)</option>
                                                    <option value="4">4 Periode</option>
                                                    <option value="5">5 Periode</option>
                                                    <option value="6">6 Periode (Semester)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="bi bi-play-fill"></i> Mulai
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- Form Filter Pencarian --}}
                            <form method="GET" action="{{ route('prediksi.index') }}" class="mb-4">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <select class="form-select" name="kode" onchange="this.form.submit()">
                                            <option value="">-- Semua Kategori Barang --</option>
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
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode barang..." value="{{ request('search') }}">
                                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i> Cari</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ route('prediksi.index') }}" class="btn btn-light w-100">Reset</a>
                                    </div>
                                </div>
                            </form>

                            {{-- Tabel Daftar Barang --}}
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Sisa Stok Gudang</th>
                                            <th class="text-center" style="width: 200px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barangList as $item)
                                        <tr>
                                            <td>{{ ($barangList->currentPage() - 1) * $barangList->perPage() + $loop->iteration }}</td>
                                            <td><span class="badge bg-secondary font-monospace">{{ $item->kodebarang }}</span></td>
                                            <td class="fw-semibold text-dark">{{ $item->namabarang }}</td>
                                            <td>{{ $item->idsatuan }}</td>
                                            <td>
                                                @if($item->stok_gudang > 10)
                                                    <span class="badge bg-success">{{ $item->stok_gudang }}</span>
                                                @elseif($item->stok_gudang > 0)
                                                    <span class="badge bg-warning text-dark">{{ $item->stok_gudang }}</span>
                                                @else
                                                    <span class="badge bg-danger">0 (Habis)</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('prediksi.detail', ['id_barang' => $item->idbarang, 'bobot_awal' => 3]) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-graph-up me-1"></i> Prediksi (WMA)
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                                Tidak ada data barang yang sesuai dengan filter pencarian.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Menampilkan {{ $barangList->firstItem() ?? 0 }} s/d {{ $barangList->lastItem() ?? 0 }} dari total {{ $barangList->total() }} barang
                                </div>
                                <div>
                                    {{ $barangList->links() }}
                                </div>
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
        function goToPrediksi() {
            const barangId = document.getElementById('selectBarangCepat').value;
            const bobot = document.getElementById('selectBobotCepat').value;
            if (barangId) {
                window.location.href = `/prediksi/${barangId}?bobot_awal=${bobot}`;
            }
        }
    </script>
</body>

</html>

