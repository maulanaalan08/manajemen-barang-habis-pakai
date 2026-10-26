<div id="sidebar" class="active">

    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">

            <div class="d-flex justify-content-between align-items-center">
                <div class="logo d-flex align-items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none text-dark">
                        <img src="{{ asset('images/rsisjs.png') }}" alt="Logo RSISJS"
                            style="width: 250px; height: 100px; object-fit: cover; border-radius: 100px; margin-left: -10px;">
                    </a>
                </div>

                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>

        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                {{-- Master Barang --}}
                <li class="sidebar-item {{ request()->is('masterBarang*') || request()->is('tambahBarang*') ? 'active' : '' }}">
                    <a href="/masterBarang" class='sidebar-link'>
                        <i class="bi bi-box-seam"></i>
                        <span>Master Barang</span>
                    </a>
                </li>

                {{-- Prediksi Kebutuhan WMA (Admin, Pengadaan, Logistik) --}}
                @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('pengadaan') || auth()->user()->hasRole('logistik'))
                <li class="sidebar-item {{ request()->is('prediksi*') ? 'active' : '' }}">
                    <a href="{{ route('prediksi.index') }}" class='sidebar-link'>
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Prediksi Kebutuhan (WMA)</span>
                    </a>
                </li>
                @endif
                @endauth

                {{-- Anggaran Belanja (Admin, Pengadaan, Logistik) --}}
                @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('pengadaan') || auth()->user()->hasRole('logistik'))
                <li class="sidebar-item has-sub {{ request()->is('anggaran*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-wallet2"></i>
                        <span>Anggaran</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('anggaran') ? 'active' : '' }}">
                            <a href="{{ route('anggaran') }}">Perencanaan Anggaran</a>
                        </li>
                        <li class="submenu-item {{ request()->is('anggaran/pembelian*') ? 'active' : '' }}">
                            <a href="{{ route('anggaran.pembelian') }}">Rekap Pembelian</a>
                        </li>
                    </ul>
                </li>
                @endif
                @endauth

                {{-- Purchase Order (Admin & Pengadaan) --}}
                @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('pengadaan'))
                <li class="sidebar-item {{ request()->is('po') || request()->is('po/barang') ? 'active' : '' }}">
                    <a href="/po" class='sidebar-link'>
                        <i class="bi bi-cart-plus"></i>
                        <span>Purchase Order</span>
                    </a>
                </li>
                @endif
                @endauth

                {{-- Penerimaan Barang (Admin & Logistik) --}}
                @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('logistik'))
                <li class="sidebar-item {{ request()->is('po/barang/masuk*') ? 'active' : '' }}">
                    <a href="/po/barang/masuk" class='sidebar-link'>
                        <i class="bi bi-box-arrow-in-down"></i>
                        <span>Penerimaan Barang</span>
                    </a>
                </li>
                @endif
                @endauth

                {{-- Anfra (Semua Role) --}}
                <li class="sidebar-item {{ request()->is('anfra*') || request()->is('proses/transaksi*') ? 'active' : '' }}">
                    <a href="/anfra" class='sidebar-link'>
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Anfra</span>
                    </a>
                </li>

                {{-- Pengeluaran Barang / Approval (Admin & Logistik) --}}
                @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('logistik'))
                <li class="sidebar-item {{ request()->is('aproval*') ? 'active' : '' }}">
                    <a href="/aproval/barang" class='sidebar-link'>
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span>Pengeluaran Barang</span>
                    </a>
                </li>
                @endif
                @endauth

                {{-- Opname Barang (Admin & Logistik) --}}
                @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('logistik'))
                <li class="sidebar-item {{ request()->is('opname*') ? 'active' : '' }}">
                    <a href="/opname" class='sidebar-link'>
                        <i class="bi bi-clipboard-check"></i>
                        <span>Opname Barang</span>
                    </a>
                </li>
                @endif
                @endauth

                {{-- Laporan (Admin, Logistik, Pengadaan) --}}
                @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('logistik') || auth()->user()->hasRole('pengadaan'))
                <li class="sidebar-item has-sub {{ request()->is('logistikLaporan*') || request()->is('laporanBarangMasuk*') || request()->is('laporan/preorder*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('logistikLaporan*') ? 'active' : '' }}">
                            <a href="/logistikLaporan">Laporan Pemakaian</a>
                        </li>
                        <li class="submenu-item {{ request()->is('laporanBarangMasuk*') ? 'active' : '' }}">
                            <a href="/laporanBarangMasuk">Laporan Barang Masuk</a>
                        </li>
                        <li class="submenu-item {{ request()->is('laporan/preorder*') ? 'active' : '' }}">
                            <a href="/laporan/preorder">Laporan Purchase Order</a>
                        </li>
                    </ul>
                </li>
                @endif
                @endauth

                {{-- Administrasi (Khusus Admin) --}}
                @auth
                @if (auth()->user()->hasRole('admin'))
                <li class="sidebar-title">Pengaturan</li>

                <li class="sidebar-item {{ request()->is('hak-akses*') ? 'active' : '' }}">
                    <a href="/hak-akses" class='sidebar-link'>
                        <i class="bi bi-shield-lock"></i>
                        <span>Hak Akses</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('register*') ? 'active' : '' }}">
                    <a href="{{ route('register') }}" class='sidebar-link'>
                        <i class="bi bi-person-plus"></i>
                        <span>Register User</span>
                    </a>
                </li>
                @endif
                @endauth

                {{-- Logout --}}
                @auth
                <li class="sidebar-item mt-3">
                    <form action="{{ route('logout') }}" method="POST" class="px-3">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</div>
