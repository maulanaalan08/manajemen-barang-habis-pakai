<!DOCTYPE html>
<html lang="id">

@include('component-web.head', ['title' => 'Hasil Prediksi WMA - ' . $barang->namabarang])

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
                    <div class="row align-items-center mb-3">
                        <div class="col-12 col-md-8">
                            <h3 class="mb-1">
                                <i class="bi bi-calculator text-primary me-2"></i>Prediksi Kebutuhan: {{ $barang->namabarang }}
                            </h3>
                            <p class="text-subtitle text-muted mb-0">
                                Kode: <span class="badge bg-secondary font-monospace">{{ $barang->kodebarang }}</span> | 
                                Satuan: <strong>{{ $barang->idsatuan }}</strong> | 
                                Metode: <strong>Weighted Moving Average ({{ $bobotAwal }} Periode)</strong>
                            </p>
                        </div>
                        <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                            <a href="{{ route('prediksi.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali ke Katalog
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Toolbar Ganti Periode Bobot & Ganti Barang --}}
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body py-3">
                        <form method="GET" action="{{ route('prediksi.detail', $barang->idbarang) }}" class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-box-seam"></i></span>
                                    <select class="form-select" onchange="if(this.value) window.location.href='/prediksi/' + this.value + '?bobot_awal={{ $bobotAwal }}'">
                                        @foreach($allBarang as $b)
                                        <option value="{{ $b->idbarang }}" {{ $b->idbarang == $barang->idbarang ? 'selected' : '' }}>
                                            {{ $b->kodebarang }} - {{ $b->namabarang }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white">Periode WMA:</span>
                                    <select name="bobot_awal" class="form-select" onchange="this.form.submit()">
                                        @for($i = 2; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bobotAwal == $i ? 'selected' : '' }}>
                                            {{ $i }} Bulan Terakhir (Bobot {{ $i }} s/d 1)
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-arrow-repeat me-1"></i> Terapkan Bobot
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- 4 Cards Indikator KPI --}}
                <div class="row g-3 mb-4">
                    {{-- Card 1: Hasil Prediksi WMA --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-white-50 text-uppercase small mb-1">Hasil Prediksi (WMA)</h6>
                                        <h2 class="text-white fw-bold mb-0">{{ number_format($totalPrediksi, 2) }}</h2>
                                        <small class="text-white-50">Dibulatkan: <strong>{{ $estimasiKebutuhan }}</strong> {{ $barang->idsatuan }}</small>
                                    </div>
                                    <i class="bi bi-graph-up-arrow fs-1 text-white-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Sisa Stok Saat Ini --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted text-uppercase small mb-1">Sisa Stok Gudang</h6>
                                        <h2 class="fw-bold mb-0 {{ $stokSaatIni <= 5 ? 'text-danger' : 'text-dark' }}">
                                            {{ $stokSaatIni }}
                                        </h2>
                                        <small class="text-muted">Satuan: {{ $barang->idsatuan }}</small>
                                    </div>
                                    <i class="bi bi-box2 fs-1 {{ $stokSaatIni <= 5 ? 'text-danger' : 'text-success' }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Status Persediaan --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted text-uppercase small mb-1">Status Persediaan</h6>
                                        <h4 class="fw-bold mb-1">
                                            @if($statusStok == 'Aman')
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Aman</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Perlu Pengadaan</span>
                                            @endif
                                        </h4>
                                        <small class="text-muted">Berdasarkan estimasi kebutuhan</small>
                                    </div>
                                    <i class="bi bi-shield-check fs-1 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: Rekomendasi Kuantitas PO --}}
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-muted text-uppercase small mb-1">Saran Pengadaan (PO)</h6>
                                        <h2 class="fw-bold mb-0 text-warning">
                                            {{ $saranPengadaan }}
                                        </h2>
                                        <small class="text-muted">Unit pesanan disarankan</small>
                                    </div>
                                    <i class="bi bi-cart-plus fs-1 text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- Grafik Pemakaian (Chart.js) --}}
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-bar-chart-line text-primary me-2"></i>Tren Pemakaian & Titik Prediksi
                                </h5>
                                <span class="badge bg-light-primary text-primary">{{ count($chartLabels) }} Periode Historis</span>
                            </div>
                            <div class="card-body">
                                @if(count($chartLabels) > 0)
                                <div style="position: relative; height: 320px;">
                                    <canvas id="wmaChart"></canvas>
                                </div>
                                @else
                                <div class="text-center py-5 text-muted">
                                    <i class="bi bi-exclamation-circle fs-1 d-block mb-3 text-warning"></i>
                                    <h5>Belum Ada Data Pemakaian</h5>
                                    <p class="mb-0">Barang ini belum memiliki catatan transaksi barang keluar di sistem.</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Rincian Perhitungan WMA --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-transparent py-3">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-table text-primary me-2"></i>Rincian Kalkulasi WMA
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Periode</th>
                                                <th class="text-center">Pemakaian ($X_i$)</th>
                                                <th class="text-center">Bobot ($W_i$)</th>
                                                <th class="text-end">Hasil ($W_i \times X_i$)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($detailPerhitungan as $item)
                                            <tr>
                                                <td class="fw-semibold">{{ $item['label'] }}</td>
                                                <td class="text-center">{{ number_format($item['pemakaian']) }}</td>
                                                <td class="text-center"><span class="badge bg-primary">{{ $item['bobot'] }}</span></td>
                                                <td class="text-end font-monospace">{{ number_format($item['hasil_kali']) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Data historis belum tersedia.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @if($jumlahData > 0)
                                        <tfoot class="table-light fw-bold">
                                            <tr>
                                                <td>Total Pembobotan</td>
                                                <td class="text-center">-</td>
                                                <td class="text-center"><span class="badge bg-dark">&Sigma; W = {{ $totalBobot }}</span></td>
                                                <td class="text-end font-monospace">&Sigma; (W&times;X) = {{ number_format($totalNilaiBobot) }}</td>
                                            </tr>
                                            <tr class="table-primary">
                                                <td colspan="3" class="text-uppercase small">Hasil Peramalan Periode Berikutnya:</td>
                                                <td class="text-end font-monospace fs-6">{{ number_format($totalPrediksi, 2) }} {{ $barang->idsatuan }}</td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light p-3 small text-muted">
                                <strong>Rumus:</strong> $\text{Prediksi} = \frac{\sum (W_i \times X_i)}{\sum W_i} = \frac{ {{ number_format($totalNilaiBobot) }} }{ {{ $totalBobot ?: 1 }} } = \mathbf{ {{ number_format($totalPrediksi, 2) }} }$
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Box: Buat PO / Buat Anggaran Berdasarkan Hasil Prediksi --}}
                <div class="card border-0 shadow-sm mt-4 bg-light-primary border-primary">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="mb-1 text-primary"><i class="bi bi-lightbulb me-2"></i>Langkah Selanjutnya</h5>
                            <p class="mb-0 text-muted">
                                Anda dapat langsung menggunakan hasil peramalan ini untuk membuat rencana anggaran belanja atau membuat Surat Pesanan (PO).
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('anggaran') }}?kode={{ substr($barang->kodebarang, 0, 3) }}" class="btn btn-outline-primary">
                                <i class="bi bi-wallet2 me-1"></i> Masuk ke Anggaran
                            </a>
                            <a href="{{ route('po.barang') }}" class="btn btn-primary">
                                <i class="bi bi-cart-plus me-1"></i> Buat PO Barang
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            @include('component-web.footer')
        </div>
    </div>

    @include('component-web.script')

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('wmaChart');
            if (!ctx) return;

            const labels = @json($chartLabels);
            const dataValues = @json($chartValues);
            const prediksiVal = {{ round($totalPrediksi, 2) }};

            // Tambahkan label periode proyeksi depan
            const labelsWithPrediksi = [...labels, 'Proyeksi Bulan Depan'];
            const dataHistoris = [...dataValues, null];
            const dataPrediksi = [...dataValues.map(() => null), prediksiVal];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsWithPrediksi,
                    datasets: [
                        {
                            label: 'Pemakaian Riil Historis',
                            data: dataHistoris,
                            backgroundColor: 'rgba(67, 94, 190, 0.75)',
                            borderColor: '#435ebe',
                            borderWidth: 1.5,
                            borderRadius: 6,
                        },
                        {
                            label: 'Prediksi WMA Periode Depan',
                            data: dataPrediksi,
                            backgroundColor: 'rgba(255, 193, 7, 0.85)',
                            borderColor: '#ffc107',
                            borderWidth: 2,
                            borderRadius: 6,
                            type: 'bar'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 14,
                                font: {
                                    family: "'Nunito', sans-serif",
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + (context.raw !== null ? context.raw + ' {{ $barang->idsatuan }}' : '-');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Unit ({{ $barang->idsatuan }})'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
