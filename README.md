# Sistem Informasi Manajemen Barang Habis Pakai (RSISJS)

Sistem Informasi Manajemen Barang Habis Pakai adalah aplikasi berbasis web yang dirancang untuk mengelola seluruh siklus pengadaan, penerimaan, inventarisasi, permintaan (*anfra*), pengeluaran, hingga stok opname barang habis pakai di lingkungan instansi/rumah sakit (**RSISJS**).

---

## 🚀 Fitur Utama Sistem

1. **Dashboard & Master Barang**
   - Pencatatan katalog barang (Kode Barang, Nama Barang, Satuan).
   - Monitoring jumlah sisa stok gudang secara *real-time*.

2. **Prediksi Kebutuhan Barang (Weighted Moving Average - WMA)**
   - Peramalan kebutuhan stok barang habis pakai untuk periode berikutnya menggunakan pembobotan historis pemakaian riil bulanan.
   - Analisis status persediaan (*Aman* atau *Perlu Pengadaan*) dan rekomendasi kuantitas pemesanan (PO).
   - Visualisasi tren konsumsi bulanan interaktif menggunakan grafik Chart.js.

3. **Perencanaan Anggaran & Anggaran Pembelian (Budgeting)**
   - **Perencanaan Anggaran**: Monitoring pemakaian bulan berjalan, sisa stok, dan harga acuan PO terakhir untuk menyusun pengajuan anggaran belanja.
   - **Rekapitulasi Anggaran Pembelian**: Filter data per bulan/tahun/kategori, kalkulasi otomatis total anggaran belanja per bulan, dan ekspor cetak PDF resmi.

4. **Purchase Order (PO) & Pengadaan**
   - Pembuatan surat pesanan (*Purchase Order*) ke supplier dengan nomor PO, harga, kuota jumlah barang, dan masa kadaluwarsa (*expired date*).
   - Rekapitulasi status pesanan.

5. **Penerimaan Barang Masuk**
   - Penerimaan barang fisik dari supplier berdasarkan nomor PO.
   - Validasi agar jumlah masuk tidak melebihi sisa kuota PO.
   - Otomatis menambah stok di master gudang dan mengurangi kuota PO.

6. **Anfra Unit (Permintaan Barang Habis Pakai)**
   - Pendaftaran barang yang dibutuhkan oleh masing-masing unit kerja/ruangan.
   - Pengajuan jumlah permintaan barang yang otomatis tervalidasi dengan ketersediaan stok gudang.
   - Monitoring riwayat pengajuan (*Pending* / *Approved*).

7. **Pengeluaran Barang (Approval Permintaan)**
   - Verifikasi dan persetujuan pengeluaran barang oleh Logistik / Super Admin.
   - Penentuan jumlah barang yang disetujui, otomatis mengurangi stok master gudang, dan mencatat riwayat barang keluar.
   - Cetak Bukti Pengeluaran Barang resmi (PDF).

8. **Stok Opname Barang**
   - Pencatatan perhitungan fisik riil barang di gudang.
   - Kalkulasi otomatis selisih stok fisik vs stok sistem, serta sinkronisasi penyesuaian stok gudang.

9. **Laporan & Ekspor PDF**
   - **Laporan Pemakaian / Barang Keluar**: Filter berdasarkan kode kategori & rentang tanggal, cetak PDF resmi.
   - **Laporan Barang Masuk**: Rekap barang masuk dari supplier per periode, cetak PDF resmi.
   - **Laporan Purchase Order**: Rekap seluruh PO beserta supplier dan jatuh tempo, cetak PDF resmi.
   - **Laporan Anggaran Pembelian**: Cetak rekapitulasi anggaran belanja bulanan ke format PDF resmi.

10. **Manajemen Pengguna & Hak Akses (RBAC)**
   - Pengaturan hak akses dinamis berbasis Role dan Permission (Spatie Permission).
   - Registrasi user baru dengan pemilihan role langsung.
   - Fitur Super-Admin universal bypass untuk akses tanpa batas.

---

## 👥 Matriks Hak Akses & Role Pengguna

| Fitur / Modul | Super Admin | Logistik | Pengadaan | Unit (Pemohon) |
| :--- | :---: | :---: | :---: | :---: |
| **Master Barang** | ✅ | ✅ | ✅ | ✅ |
| **Tambah Master Barang** | ✅ | ❌ | ❌ | ❌ |
| **Prediksi Kebutuhan (WMA)** | ✅ | ✅ | ✅ | ❌ |
| **Perencanaan Anggaran** | ✅ | ✅ | ✅ | ❌ |
| **Rekap Anggaran Pembelian** | ✅ | ✅ | ✅ | ❌ |
| **Purchase Order (PO)** | ✅ | ❌ | ✅ | ❌ |
| **Penerimaan Barang Masuk** | ✅ | ✅ | ❌ | ❌ |
| **Pengajuan Anfra Unit** | ✅ | ✅ | ✅ | ✅ |
| **Pengeluaran / Approval Anfra** | ✅ | ✅ | ❌ | ❌ |
| **Stok Opname Barang** | ✅ | ✅ | ❌ | ❌ |
| **Laporan & Cetak PDF** | ✅ | ✅ | ✅ | ❌ |
| **Manajemen Hak Akses** | ✅ | ❌ | ❌ | ❌ |
| **Register User Baru** | ✅ | ❌ | ❌ | ❌ |
| **Register User Baru** | ✅ | ❌ | ❌ | ❌ |

---

## 🛠️ Teknologi & Kebutuhan Sistem

- **PHP**: Versi 8.1 atau lebih baru
- **Framework**: [Laravel 10](https://laravel.com/)
- **Database**: MySQL / MariaDB
- **Autentikasi**: Laravel Breeze
- **Role & Permission**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- **PDF Engine**: [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- **Frontend Template**: Mazer Dashboard (Bootstrap 5 & Bootstrap Icons)

---

## 💻 Panduan Instalasi & Menjalankan Proyek

### 1. Clone Repositori
```bash
git clone https://github.com/<username-anda>/<nama-repo>.git
cd projek-kp
```

### 2. Install Dependensi PHP & JavaScript
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env` dan sesuaikan pengaturan database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=projekkp
DB_USERNAME=root
DB_PASSWORD=
```
Lalu generate application key:
```bash
php artisan key:generate
```

### 4. Menjalankan Migrasi & Seeder Database
Pastikan MySQL sudah berjalan (misal via XAMPP / Laragon), lalu jalankan:
```bash
php artisan migrate --seed
```

### 5. Akun Login Default
Setelah seeder dijalankan, akun bawaan yang siap digunakan:
- **Super Admin**:
  - Username: `admin` | Password: `admin`
  - Username: `superadmin` | Password: `admin`

---

## ⚡ Perintah Cepat Super Admin

Aplikasi ini dilengkapi dengan Artisan Command khusus untuk memudahkan pengelolaan hak akses:

### Menjadikan Seluruh User Menjadi Super Admin
Untuk memberikan akses penuh (role `admin`) ke semua user yang ada di database:
```bash
php artisan user:superadmin
```

### Menjadikan User Tertentu Sebagai Super Admin
```bash
php artisan user:superadmin <nama_username>
```
*(Jika user belum terdaftar, sistem akan otomatis menawarkan pembuatan user baru beserta password).*

---

## 📤 Panduan Push ke Repositori GitHub

Untuk mengunggah kode ke GitHub pribadi Anda:

1. Buat repositori baru di GitHub (misal: `https://github.com/username-anda/projek-kp.git`).
2. Di terminal folder proyek ini, jalankan perintah berikut:
```bash
# Inisialisasi Git
git init

# Tambahkan semua file dan buat commit awal
git add .
git commit -m "feat: Sistem Informasi Manajemen Barang Habis Pakai RSISJS"

# Ubah nama branch utama ke main
git branch -M main

# Hubungkan dengan repositori GitHub Anda
git remote add origin https://github.com/<username-anda>/<nama-repo>.git

# Unggah (push) kode ke GitHub
git push -u origin main
```

---

## 📄 Lisensi
Proyek ini dikembangkan untuk kebutuhan Kerja Praktek (KP) / Sistem Manajemen Barang Habis Pakai Rumah Sakit.
