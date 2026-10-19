# 🏛️ Sistem Informasi Pelayanan Surat Keterangan Usaha (Sistem SKU)
### Pemerintah Kalurahan Condongcatur, Kapanewon Depok, Kabupaten Sleman

![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue?logo=php)
![CodeIgniter](https://img.shields.io/badge/Framework-CodeIgniter%204-EF4444?logo=codeigniter)
![Database](https://img.shields.io/badge/Database-MySQL-4479A1?logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green)

Aplikasi web terintegrasi untuk digitalisasi pelayanan publik dalam pengajuan, verifikasi, pengesahan, dan penerbitan **Surat Keterangan Usaha (SKU)** bagi masyarakat dan pelaku usaha mikro, kecil, dan menengah (UMKM).

---

## 📋 Daftar Isi
1. [Tentang Sistem](#-tentang-sistem)
2. [Fitur Utama](#-fitur-utama)
3. [Alur Pelayanan Digital](#-alur-pelayanan-digital)
4. [Hak Akses & Role Pengguna](#-hak-akses--role-pengguna)
5. [Akun Demo / Testing](#-akun-demo--testing)
6. [Struktur Database](#-struktur-database)
7. [Prasyarat Sistem](#-prasyarat-sistem)
8. [Panduan Instalasi](#-panduan-instalasi)
9. [Struktur Direktori](#-struktur-direktori)
10. [Lisensi](#-lisensi)

---

## 📖 Tentang Sistem

Sistem SKU hadir sebagai solusi pelayanan administratif desa/kalurahan yang cepat, transparan, dan akuntabel. Warga tidak perlu lagi mengantre panjang di kantor kelurahan; cukup mendaftar dan mengajukan permohonan melalui portal daring, memantau status secara *real-time*, hingga mengunduh surat resmi yang telah disahkan secara digital dengan validasi **QR Code**.

---

## ✨ Fitur Utama

- 🌐 **Portal Publik & Landing Page**: Informasi persyaratan, panduan alur, serta profil resmi kalurahan.
- 🔐 **Autentikasi Aman**: Registrasi warga berbasis NIK 16 digit, enkripsi password Bcrypt, dan filter hak akses per role.
- 📄 **Pengajuan SKU Baru & Perpanjangan**: Formulir lengkap mencakup data pemohon, profil usaha (modal, omset, alamat, lama usaha), serta unggah berkas persyaratan.
- 📎 **Manajemen Dokumen Persyaratan**: Unggah berkas persyaratan (KTP, KK, Surat Pengantar RT/RW, Foto Usaha, dan SKU lama jika perpanjangan).
- 🔍 **Verifikasi Staf & Catatan Revisi**: Staf kalurahan dapat memvalidasi dokumen satu per satu dan memberikan feedback revisi transparan bila data belum lengkap.
- 🖋️ **Approval & Pengesahan Digital oleh Lurah**: Disahkan langsung oleh pimpinan kalurahan lengkap dengan nomor surat otomatis.
- 🛡️ **Autentikasi QR Code**: Menjamin keaslian surat dan mencegah pemalsuan dokumen fisik.
- 📜 **Riwayat Status (Audit Trail)**: Pencatatan otomatis setiap perpindahan status permohonan.
- ⚙️ **Konfigurasi Kalurahan Dinamis**: Pengaturan nama kalurahan, pejabat penandatangan, NIP, kontak, logo, serta pola penomoran surat.

---

## 🔄 Alur Pelayanan Digital

```text
[ Pemohon ] ──> Mengisi Data & Unggah Berkas Persyaratan (Draft / Diajukan)
                       │
                       ▼
[  Staf   ] ──> Verifikasi Dokumen & Data Usaha
                       ├──> Perlu Perbaikan? ──> Status: Revisi (Warga upload ulang)
                       └──> Lengkap & Valid  ──> Status: Menunggu Persetujuan Lurah
                                                      │
                                                      ▼
[  Lurah  ] ──────────────────────────> Persetujuan & Penomoran Resmi
                                                      │
                                                      ▼
[ Selesai ] <── Surat Resmi Terbit dengan QR Code & Siap Dicetak Pemohon
```

---

## 👥 Hak Akses & Role Pengguna

| Role | Deskripsi & Wewenang |
|---|---|
| **Admin** | Mengelola seluruh akun pengguna, master data kalurahan, format penomoran surat, dan monitoring sistem. |
| **Staf** | Memeriksa berkas permohonan yang masuk, validasi dokumen, meminta revisi, dan meneruskan berkas valid ke Lurah. |
| **Lurah** | Meninjau berkas yang telah diverifikasi staf, memberikan persetujuan akhir (*approval*), dan pengesahan surat. |
| **Pemohon** | Warga/pelaku usaha yang mengajukan SKU, memantau proses verifikasi, dan mengunduh surat resmi. |

---

## 🔑 Akun Demo / Testing

Data akun berikut telah disediakan secara otomatis melalui `UserSeeder`:

| Role | Email / NIK | Password Default | Nama Pengguna |
|---|---|---|---|
| **Admin** | `admin@kalurahan.go.id` | `admin123` | Administrator Kalurahan |
| **Staf** | `staf@kalurahan.go.id` | `staf123` | Budi Santoso, S.Sos (Staf Pelayanan) |
| **Lurah** | `lurah@kalurahan.go.id` | `lurah123` | Drs. H. Reno Candra, M.Si (Lurah) |
| **Pemohon** | `pemohon@gmail.com` | `pemohon123` | Ahmad Kurniawan (Warga / Pemohon) |

> ⚠️ *Untuk penggunaan produksi, harap ubah semua password default ini.*

---

## 🗄️ Struktur Database

Sistem dibangun menggunakan migrasi database CodeIgniter 4 yang rapi:

1. **`users`**: Menyimpan kredensial pengguna, NIK, peran/role, dan status akun.
2. **`pengaturan_kalurahan`**: Konfigurasi profil kalurahan, nama lurah, NIP, logo, dan format surat.
3. **`permohonan_sku`**: Data pengajuan SKU, rincian usaha, status workflow, nomor surat, tanggal berlaku, dan token QR.
4. **`dokumen_persyaratan`**: Berkas lampiran permohonan (KTP, KK, foto usaha, dll.) beserta status validasi dokumen.
5. **`riwayat_status`**: Log riwayat perubahan status pengajuan untuk transparansi dan audit trail.

---

## 💻 Prasyarat Sistem

- **PHP** versi 8.1 atau lebih baru (PHP 8.2 direkomendasikan)
- **Ekstensi PHP**: `intl`, `mbstring`, `mysqli`, `gd`, `curl`
- **Database**: MySQL 5.7+ / MariaDB 10.4+
- **Composer** (Dependency Manager)
- **Lingkungan Lokal**: Laragon / XAMPP

---

## 🚀 Panduan Instalasi

### 1. Clone atau Buka Proyek
Pastikan folder proyek berada di direktori web server (misal: `C:\laragon\www\sistem-sku`).

### 2. Pasang Dependensi
Buka terminal pada folder proyek, lalu jalankan:
```bash
composer install
```

### 3. Konfigurasi File Lingkungan (`.env`)
Salin file `env` menjadi `.env` (atau edit file `.env` yang sudah ada) dan sesuaikan:
```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'
# Atau jika menggunakan Virtual Host Laragon:
# app.baseURL = 'http://sistem-sku.test/'

database.default.hostname = localhost
database.default.database = sistem_sku
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Buat Database & Jalankan Migrasi
Buat database baru di MySQL dengan nama **`sistem_sku`**, kemudian jalankan perintah migrasi dan seeder di terminal:
```bash
# Jalankan migrasi tabel
php spark migrate

# Masukkan data awal (Pengguna Demo & Pengaturan Kalurahan)
php spark db:seed DatabaseSeeder
```

### 5. Jalankan Server Pengembangan
Jalankan aplikasi dengan perintah:
```bash
php spark serve
```
Buka browser dan akses:
👉 **`http://localhost:8080`** (atau `http://sistem-sku.test` jika menggunakan virtual host Laragon).

---

## 📁 Struktur Direktori

```text
sistem-sku/
├── app/
│   ├── Config/              # Konfigurasi aplikasi, routes, database, filter
│   ├── Controllers/         # Controller (Admin, Staf, Lurah, Pemohon, Auth, Home)
│   ├── Database/
│   │   ├── Migrations/      # Skrip pembuatan tabel database
│   │   └── Seeds/           # Data awal/dummy akun dan pengaturan
│   ├── Filters/             # Filter autentikasi dan otorisasi role
│   ├── Models/              # Model data CodeIgniter
│   └── Views/               # Tampilan UI (Blade-like PHP templates)
│       ├── admin/           # Dashboard & fitur Administrator
│       ├── auth/            # Halaman Login & Registrasi
│       ├── layouts/         # Layout utama & komponen navigasi
│       ├── lurah/           # Dashboard & verifikasi Lurah
│       ├── pemohon/         # Dashboard & pengajuan warga
│       └── staf/            # Dashboard & verifikasi Staf
├── public/                  # Aset publik (CSS, JS, gambar, upload)
├── writable/                # File cache, sesi, dan log sistem
├── .env                     # Konfigurasi environment lokal
├── composer.json            # Daftar pustaka PHP
└── README.md                # Dokumentasi proyek
```

---

## 📄 Lisensi
Proyek ini dikembangkan di bawah lisensi [MIT License](LICENSE).
Sistem ini bebas digunakan dan disesuaikan untuk kebutuhan instansi pemerintah desa/kelurahan lainnya.
