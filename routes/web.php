<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnfraController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\AnggaranController;
use App\Models\Preorder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware(['auth', 'verified', 'role:admin'])->name('register');
Route::post('/register/store', [RegisteredUserController::class, 'store'])->middleware(['auth', 'verified', 'role:admin']);
require __DIR__ . '/auth.php';

//admin
Route::get('/hak-akses', [AdminController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('hak-akses');
Route::get('/get-permissions-by-role/{roleId}', [AdminController::class, 'getPermissionsByRole']);
Route::post('/update-role-permissions', [AdminController::class, 'updatePermissions'])->name('roles.update-permissions');

// GUDANG
Route::get('/masterBarang', [PengadaanController::class, 'adminIndex2'])->middleware(['auth', 'verified', 'role:admin|logistik|unit|pengadaan'])->name('dashboard');

Route::get('/tambahBarang', [PengadaanController::class, 'tambahBarang'])->middleware(['auth', 'verified', 'role:admin|logistik|unit'])->name('barang.tambah.view');
Route::post('/masterBarang', [PengadaanController::class, 'barang'])->middleware(['auth', 'verified', 'role:admin|logistik|unit'])->name('barang.tambah');

Route::get('/aproval/barang', [PengadaanController::class, 'aproval'])->middleware(['auth', 'verified', 'role:admin|logistik'])->name('aproval.barang');
Route::post('/aproval/barang/{id_transaksi_unit}', [PengadaanController::class, 'terima'])->middleware(['auth', 'verified', 'role:admin|logistik'])->name('barang.acc');

//  PO
Route::get('/po', [PreorderController::class, 'view'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('po.view');
Route::get('/po/barang', [PreorderController::class, 'index'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('po.barang');
Route::post('/po/barang/tambah', [PreorderController::class, 'tambah'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('po.tambah');

Route::get('/po/barang/masuk', [PreorderController::class, 'pomasuk'])->middleware(['auth', 'verified', 'role:admin|logistik'])->name('po.masuk');
Route::post('po/barang/acc', [PreorderController::class, 'barangMasuk'])->middleware(['auth', 'verified', 'role:admin|logistik'])->name('po.acc');
Route::get('/laporan/preorder', [PreorderController::class, 'laporan'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('laporan.preorder');
Route::get('/laporan/preorder/cetak', [CetakController::class, 'preorderCetak'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('cetak.preorder');

//ANFRA
Route::get('/anfra', [AnfraController::class, 'adminIndex3'])->middleware(['auth', 'verified', 'role:admin|unit|logistik|pengadaan'])->name('anfra');
Route::get('anfra/barang/unit', [AnfraController::class, 'tambah'])->middleware(['auth', 'verified', 'role:admin|unit|logistik'])->name('anfra.tambah');
Route::get('/proses/transaksi', [AnfraController::class, 'prosesTransaksi'])->middleware(['auth', 'verified', 'role:admin|unit|logistik'])->name('proses.transaksi');

Route::post('/anfra/barang', [AnfraController::class, 'barang'])->middleware(['auth', 'verified', 'role:admin|unit|logistik'])->name('anfra.barang');
Route::post('/anfra/stok', [AnfraController::class, 'stok'])->middleware(['auth', 'verified', 'role:admin|unit|logistik'])->name('anfra.stok');

//cetak bukti anfra
Route::get('/transaksi/cetak/{id}', [CetakController::class, 'transaksiUnit'])->middleware(['auth', 'verified'])->name('transaksi.cetak');
Route::get('/transaksi/logistik/{id}', [CetakController::class, 'transaksiLogistik'])->middleware(['auth', 'verified'])->name('transaksi.logistik');

Route::get('/logistikLaporan', [PengadaanController::class, 'laporan'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('logistikLaporan');
Route::get('/laporanBarangKeluar', [CetakController::class, 'barangKeluar'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('laporan.keluar');

Route::get('/laporanBarangMasuk', [PengadaanController::class, 'laporanMasuk'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('laporan.masuk');
Route::get('/laporanBarangMasuk/cetak', [CetakController::class, 'barangMasuk'])->middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->name('cetak.laporan');

//opname barang
Route::get('/opname', [OpnameController::class, 'index3'])->middleware(['auth', 'verified', 'role:admin|logistik'])->name('opname.barang');
Route::post('/opname/masuk', [OpnameController::class, 'tambah'])->middleware(['auth', 'verified', 'role:admin|logistik'])->name('opname.masuk');

// PREDIKSI KEBUTUHAN BARANG (WMA)
Route::middleware(['auth', 'verified', 'role:admin|logistik|pengadaan'])->group(function () {
    Route::get('/prediksi', [PrediksiController::class, 'index'])->name('prediksi.index');
    Route::get('/prediksi/pergerakan', [PrediksiController::class, 'index'])->name('prediksi.pergerakan');
    Route::get('/prediksi/{id_barang}', [PrediksiController::class, 'prediksi'])->name('prediksi.detail');

    // PERENCANAAN ANGGARAN & ANGGARAN PEMBELIAN
    Route::get('/anggaran', [AnggaranController::class, 'index'])->name('anggaran');
    Route::post('/anggaran/masuk', [AnggaranController::class, 'store'])->name('anggaran.masuk');
    Route::get('/anggaran/pembelian', [AnggaranController::class, 'pembelian'])->name('anggaran.pembelian');
    Route::get('/anggaran/pembelian/cetak', [AnggaranController::class, 'cetak'])->name('anggaran.cetak');
});

