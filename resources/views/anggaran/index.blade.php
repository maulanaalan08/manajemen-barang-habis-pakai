<!DOCTYPE html>
<html lang="id">

@include('component-web.head', ['title' => 'Perencanaan Anggaran Pengadaan Barang'])

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
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3><i class="bi bi-wallet2 text-primary me-2"></i>Perencanaan Anggaran Pengadaan</h3>
                            <p class="text-subtitle text-muted">Rencanakan estimasi kebutuhan kuantitas dan alokasi anggaran belanja barang habis pakai.</p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first text-md-end">
                            <a href="{{ route('anggaran.pembelian') }}" class="btn btn-outline-primary me-2">
                                <i class="bi bi-receipt me-1"></i> Rekap Anggaran Pembelian
                            </a>
                            <a href="{{ route('po.barang') }}" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-1"></i> Buat PO
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <section class="section">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-transparent py-3">
                            <form method="GET" action="{{ route('anggaran') }}" class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label small fw-semibold">Pilih Kategori Barang:</label>
                                    <select id="kode" name="kode" class="form-select" onchange="this.form.submit()">
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
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filter</button>
                                </div>
                                <div class="col-md-5 text-md-end">
                                    <span class="badge bg-light-primary text-primary fs-6 p-2">
                                        Total: {{ $barang->count() }} Item Barang
                                    </span>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle" id="table1">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">No</th>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th class="text-center">Sisa Stok</th>
                                            <th class="text-center">Keluar Bulan Ini</th>
                                            <th class="text-end">Harga Terakhir</th>
                                            <th>Supplier Acuan</th>
                                            <th class="text-center" style="width: 140px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($barang as $db)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span class="badge bg-secondary font-monospace">{{ $db->kodebarang }}</span></td>
                                            <td class="fw-semibold">{{ $db->namabarang }}</td>
                                            <td>{{ $db->idsatuan }}</td>
                                            <td class="text-center">
                                                @if($db->stok_gudang > 10)
                                                    <span class="badge bg-success">{{ $db->stok_gudang }}</span>
                                                @elseif($db->stok_gudang > 0)
                                                    <span class="badge bg-warning text-dark">{{ $db->stok_gudang }}</span>
                                                @else
                                                    <span class="badge bg-danger">0</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light-danger text-danger fw-bold">{{ $db->pemakaian_bulan_ini }}</span>
                                            </td>
                                            <td class="text-end font-monospace">
                                                Rp {{ number_format($db->harga_terakhir, 0, ',', '.') }}
                                            </td>
                                            <td class="small text-muted">{{ $db->suplier_terakhir }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAnggaran{{ $db->idbarang }}">
                                                    <i class="bi bi-plus-circle me-1"></i> Rencana
                                                </button>

                                                {{-- Modal Input Anggaran --}}
                                                <div class="modal fade" id="modalAnggaran{{ $db->idbarang }}" tabindex="-1" aria-labelledby="modalLabel{{ $db->idbarang }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content text-start">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title text-white" id="modalLabel{{ $db->idbarang }}">
                                                                    <i class="bi bi-wallet-fill me-2"></i>Rencana Anggaran: {{ $db->namabarang }}
                                                                </h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>

                                                            <form action="{{ route('anggaran.masuk') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="barang_id" value="{{ $db->idbarang }}">

                                                                <div class="modal-body">
                                                                    <div class="bg-light p-3 rounded mb-3 small">
                                                                        <div class="row">
                                                                            <div class="col-6">Kode: <strong>{{ $db->kodebarang }}</strong></div>
                                                                            <div class="col-6">Satuan: <strong>{{ $db->idsatuan }}</strong></div>
                                                                            <div class="col-6 mt-1">Sisa Stok: <strong>{{ $db->stok_gudang }}</strong></div>
                                                                            <div class="col-6 mt-1">Keluar Bln Ini: <strong>{{ $db->pemakaian_bulan_ini }}</strong></div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Tanggal Perencanaan</label>
                                                                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Harga Satuan (Rp)</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text">Rp</span>
                                                                            <input type="number" name="harga" class="form-control" value="{{ $db->harga_terakhir }}" min="0" required>
                                                                        </div>
                                                                        <small class="text-muted">Acuan dari PO terakhir: Rp {{ number_format($db->harga_terakhir, 0, ',', '.') }}</small>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Jumlah Kuantitas Pembelian</label>
                                                                        <input type="number" name="jumlah" class="form-control" min="1" placeholder="Masukkan jumlah unit barang..." required>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-semibold">Catatan / Keterangan</label>
                                                                        <input type="text" name="catatan" class="form-control" placeholder="Contoh: Kebutuhan operasional triwulan, stok menipis...">
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">
                                                                        <i class="bi bi-save me-1"></i> Simpan Rencana Anggaran
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">Data barang tidak ditemukan.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
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
