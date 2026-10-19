# 🏛️ SISTEM INFORMASI PELAYANAN SURAT KETERANGAN USAHA (SISTEM-SKU)
### *Digital Public Service Portal for Enterprise Government Administration*

<p align="center">
  <img src="public/images/logo-kalurahan.svg" alt="Logo Kalurahan" width="100" height="100" style="margin-bottom: 12px;" />
  <br>
  <strong>Pemerintah Kalurahan Condongcatur, Kapanewon Depok, Kabupaten Sleman</strong>
  <br>
  <em>Platform E-Government Terintegrasi Pelayanan Surat Keterangan Usaha (SKU) Berbasis Web</em>
</p>

<p align="center">
  <a href="#-tech-stack"><img src="https://img.shields.io/badge/PHP-8.1%20%7C%208.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version" /></a>
  <a href="#-tech-stack"><img src="https://img.shields.io/badge/Framework-CodeIgniter%204.x-EF4444?style=for-the-badge&logo=codeigniter&logoColor=white" alt="Framework" /></a>
  <a href="#-tech-stack"><img src="https://img.shields.io/badge/Database-MySQL%20%2F%20MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" /></a>
  <a href="#-keamanan--kepatuhan"><img src="https://img.shields.io/badge/Security-RBAC%20%26%20CSRF%20Protected-10B981?style=for-the-badge&logo=auth0&logoColor=white" alt="Security" /></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-0ea5e9?style=for-the-badge" alt="License" /></a>
</p>

---

## 📑 Daftar Isi
- [1. Executive Summary](#1-executive-summary)
- [2. Arsitektur Sistem & Alur Bisnis](#2-arsitektur-sistem--alur-bisnis)
- [3. Matriks Hak Akses (RBAC)](#3-matriks-hak-akses-rbac)
- [4. Modul & Fitur Utama](#4-modul--fitur-utama)
- [5. Standar Keamanan & Validasi](#5-standar-keamanan--validasi)
- [6. Skema Database & Relasi](#6-skema-database--relasi)
- [7. Spesifikasi Route & Endpoint](#7-spesifikasi-route--endpoint)
- [8. Panduan Instalasi & Deployment](#8-panduan-instalasi--deployment)
- [9. Data Awal & Kredensial Pengujian](#9-data-awal--kredensial-pengujian)
- [10. Standar Kontribusi & Git Workflow](#10-standar-kontribusi--git-workflow)
- [11. Lisensi & Kontak](#11-lisensi--kontak)

---

## 1. Executive Summary

**Sistem-SKU** adalah aplikasi enterprise e-Government yang dirancang untuk mereformasi tata kelola pelayanan publik di tingkat desa/kalurahan. Sistem ini mentransformasikan alur pengurusan Surat Keterangan Usaha yang konvensional dan birokratis menjadi layanan digital nir-kertas (*paperless*) yang transparan, terukur, dan akuntabel.

### Sasaran & Dampak:
- **Efisiensi Waktu**: Memangkas waktu tunggu pengurusan dari 2–3 hari kerja menjadi hitungan jam.
- **Integritas Dokumen**: Mencegah pemalsuan surat menggunakan token kriptografi dan verifikasi **QR Code Dinamis**.
- **Audit Trail Penuh**: Setiap mutasi status pengajuan dicatat ke dalam log riwayat yang tidak dapat dimanipulasi.
- **Kemudahan Aksesibilitas**: Akses mandiri 24/7 bagi pelaku UMKM dari perangkat komputer maupun smartphone.

---

## 2. Arsitektur Sistem & Alur Bisnis

Aplikasi dibangun di atas pola arsitektur **Model-View-Controller (MVC)** terstruktur menggunakan framework CodeIgniter 4 dengan pemisahan tanggung jawab (*separation of concerns*) yang ketat.

### Alur Bisnis Permohonan (State Diagram):

```
       [ PEMOHON ]
            │ (Submit Berkas & Data Usaha)
            ▼
    ┌───────────────┐
    │     DRAFT     │
    └───────┬───────┘
            │
            ▼
    ┌───────────────┐
    │   DIAJUKAN    │ ◄────────────────────────┐
    └───────┬───────┘                          │
            │                                  │
      (Verifikasi Staf)                        │
            │                                  │
    ┌───────┴───────────────────────┐          │
    ▼                               ▼          │
┌────────────────┐          ┌──────────────┐   │ (Pemohon perbaiki
│     REVISI     │          │  VERIFIKASI  │   │  dokumen syarat)
└───────┬────────┘          │     STAF     │───┘
        │                   └───────┬──────┘
        └───────────────────────────┤
                                    │ (Diteruskan ke Pimpinan)
                                    ▼
                     ┌─────────────────────────────┐
                     │ MENUNGGU PERSETUJUAN LURAH  │
                     └──────────────┬──────────────┘
                                    │
                       (Otorisasi & TTD Lurah)
                                    │
                     ┌──────────────┴──────────────┐
                     ▼                             ▼
             ┌──────────────┐              ┌───────────────┐
             │   DISETUJUI  │              │    DITOLAK    │
             │  (Surat Terbit)             └───────────────┘
             └───────┬──────┘
                     │
                     ▼
             ┌──────────────┐
             │   SELESAI    │ (Cetak Mandiri Ber-QR Code)
             └──────────────┘
```

---

## 3. Matriks Hak Akses (RBAC)

Sistem menerapkan **Role-Based Access Control (RBAC)** berbasis middleware filter (`app/Filters/RoleFilter.php`):

| Fitur / Modul | Public / Guest | Pemohon (Warga) | Staf Pelayanan | Lurah | Administrator |
|---|:---:|:---:|:---:|:---:|:---:|
| **Landing Page & Panduan** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Registrasi Akun (NIK Valid)** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Login / Logout** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Pengajuan SKU Baru & Perpanjangan** | ❌ | ✅ | ❌ | ❌ | ❌ |
| **Tracking Status & Riwayat Pengajuan** | ❌ | ✅ | ✅ | ✅ | ✅ |
| **Verifikasi Dokumen Persyaratan** | ❌ | ❌ | ✅ | ❌ | ✅ |
| **Kirim Catatan Revisi ke Warga** | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Persetujuan / Penolakan Final (Approval)** | ❌ | ❌ | ❌ | ✅ | ❌ |
| **Generate Nomor Surat Otomatis** | ❌ | ❌ | ❌ | ✅ | ✅ |
| **Unduh / Cetak Surat Resmi (PDF)** | ❌ | ✅ | ✅ | ✅ | ✅ |
| **Validasi Publik via QR Code** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Konfigurasi Instansi & Format Surat** | ❌ | ❌ | ❌ | ❌ | ✅ |
| **Manajemen Pengguna & Audit Log** | ❌ | ❌ | ❌ | ❌ | ✅ |

---

## 4. Modul & Fitur Utama

### 4.1 Modul Pemohon (Warga & Pelaku Usaha)
- **Formulir Pengajuan Terstruktur**: Validasi input identitas, lama usaha, estimasi modal usaha, omset per bulan, jumlah tenaga kerja, serta status kepemilikan tempat usaha.
- **Unggah Dokumen Syarat**: Sistem drag-and-drop dokumen legalitas (KTP, Kartu Keluarga, Pengantar RT/RW, foto aktivitas usaha, dan SKU lama jika perpanjangan).
- **Notifikasi Revisi Real-time**: Catatan korektif dari staf langsung tertera transparan pada kartu status berkas.

### 4.2 Modul Staf Pelayanan
- **Verification Workbench**: Panel tinjauan dokumen bersanding (*side-by-side*) dengan status verifikasi per lembar dokumen (*valid*, *tidak valid*, *pending*).
- **Workflow Forwarding**: Fitur pengalihan pengajuan yang telah lolos uji berkas ke meja persetujuan Lurah.

### 4.3 Modul Lurah (Executive Approval)
- **Executive Dashboard**: Ringkasan beban permohonan aktif, statistik pengajuan bulanan, dan histori surat keluar.
- **Digital Sign-Off**: Otorisasi sekali klik yang memicu generator nomor surat dinamis dan pembuatan token hash QR Code.

### 4.4 Modul Keamanan & Anti-Pemalsuan
- **QR Code Security Seal**: Dokumen fisik yang dicetak warga memuat QR Code unik menuju endpoint validasi publik resmi untuk memastikan keaslian data surat tanpa perlu legalisir stempel basah berulang.

---

## 5. Standar Keamanan & Validasi

Aplikasi dibangun dengan mematuhi prinsip keamanan standar industri:

- **Enkripsi Kredensial**: Hashing password menggunakan algoritma industri `Bcrypt` dengan *salt cost* standar.
- **CSRF Token Protection**: Seluruh formulir POST dilindungi token anti-CSRF yang di-refresh secara berkala.
- **SQL Injection Prevention**: Penggunaan Query Builder dan Prepared Statements secara menyeluruh pada layer Model.
- **XSS & Content Security**: Penyaringan output menggunakan fungsi `esc()` di setiap rendering view.
- **File Upload Hardening**: Validasi ganda berkas upload (ekstensi, ukuran maksimum, dan pemeriksaan MIME type asli) pada folder non-eksekusi.
- **Role Isolation**: Middleware filter memastikan pengguna tidak dapat mengakses route di luar hak istimewanya (*privilege escalation prevention*).

---

## 6. Skema Database & Relasi

Aplikasi menggunakan engine relasional **MySQL/MariaDB** dengan integritas referensial penuh:

```
  ┌────────────────┐
  │     users      │
  └───────┬────────┘
          │ 1
          │
          │ N
  ┌───────┴───────────────┐ 1         N ┌──────────────────────────┐
  │    permohonan_sku     │────────────│   dokumen_persyaratan    │
  └───────┬───────────────┘             └──────────────────────────┘
          │ 1
          │
          │ N
  ┌───────┴───────────────┐
  │    riwayat_status     │
  └───────────────────────┘
```

### Rincian Tabel Inti:
1. **`users`**: Entitas pengguna terpusat (`id`, `nik`, `nama_lengkap`, `email`, `password_hash`, `role`, `status_aktif`, timestamps).
2. **`pengaturan_kalurahan`**: Konfigurasi instansi (`nama_kalurahan`, `kapanewon`, `nama_lurah`, `nip_lurah`, `format_nomor_surat`, logo).
3. **`permohonan_sku`**: Rekod transaksi SKU (`kode_pengajuan`, `tipe_pengajuan`, identitas usaha, finansial, status workflow, `nomor_surat`, `qr_token`).
4. **`dokumen_persyaratan`**: Berkas lampiran permohonan (`permohonan_id`, `jenis_dokumen`, `file_path`, `status_verifikasi`, `catatan`).
5. **`riwayat_status`**: Log jejak audit pergantian status (`permohonan_id`, `status_sebelumnya`, `status_baru`, `user_id`, `keterangan`).

---

## 7. Spesifikasi Route & Endpoint

| Method | URI / Route | Controller & Action | Otorisasi / Filter |
|---|---|---|---|
| `GET` | `/` | `Home::index` | Publik |
| `GET` / `POST` | `/login` | `Auth::login` / `processLogin` | Guest |
| `GET` / `POST` | `/register` | `Auth::register` / `processRegister` | Guest |
| `GET` | `/logout` | `Auth::logout` | Authenticated |
| `GET` | `/admin/dashboard` | `Admin\Dashboard::index` | Role: `admin` |
| `GET` | `/staf/dashboard` | `Staf\Dashboard::index` | Role: `staf` |
| `GET` | `/lurah/dashboard` | `Lurah\Dashboard::index` | Role: `lurah` |
| `GET` | `/pemohon/dashboard`| `Pemohon\Dashboard::index`| Role: `pemohon` |

---

## 8. Panduan Instalasi & Deployment

### 8.1 Prasyarat Sistem
- **PHP**: `^8.1` atau `^8.2` (dengan ekstensi `ext-intl`, `ext-mbstring`, `ext-mysqli`, `ext-curl`, `ext-gd`)
- **Composer**: `^2.x`
- **Web Server**: Apache / Nginx / Laragon
- **Database Server**: MySQL `^8.0` atau MariaDB `^10.4`

### 8.2 Langkah Instalasi Lokal

1. **Kloning Repository**
   ```bash
   git clone https://github.com/Fatikasari1206/sistem-informasi-sku.git
   cd sistem-informasi-sku
   ```

2. **Instalasi Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment (`.env`)**
   Salin berkas `env` default ke `.env`:
   ```bash
   cp env .env
   ```
   Buka file `.env` dan konfigurasikan parameter database serta URL aplikasi:
   ```ini
   CI_ENVIRONMENT = development

   app.baseURL = 'http://localhost:8080/'
   # Jika menggunakan Laragon virtual host:
   # app.baseURL = 'http://sistem-sku.test/'

   database.default.hostname = localhost
   database.default.database = sistem_sku
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

4. **Eksekusi Migrasi & Seeding Database**
   Pastikan database `sistem_sku` telah dibuat di MySQL, lalu jalankan:
   ```bash
   # Menjalankan migrasi struktur tabel
   php spark migrate

   # Memasukkan data awal (User Role & Master Profil Kalurahan)
   php spark db:seed DatabaseSeeder
   ```

5. **Menjalankan Server Aplikasi**
   ```bash
   php spark serve
   ```
   Akses portal melalui browser di: 👉 **`http://localhost:8080`**

---

## 9. Data Awal & Kredensial Pengujian

Gunakan kredensial pengujian berikut yang dibuat otomatis oleh `DatabaseSeeder`:

| Role Pengguna | Identitas (Email / NIK) | Password Default | Keterangan & Tugas |
|---|---|---|---|
| **Administrator** | `admin@kalurahan.go.id` <br>`3404010101900001` | `admin123` | Konfigurasi sistem dan manajemen akun |
| **Staf Pelayanan** | `staf@kalurahan.go.id` <br>`3404010202920002` | `staf123` | Verifikasi kelayakan dokumen pemohon |
| **Lurah** | `lurah@kalurahan.go.id` <br>`3404010303800003` | `lurah123` | Otorisasi & pengesahan tanda tangan surat |
| **Pemohon (Warga)** | `pemohon@gmail.com` <br>`3404011508950004` | `pemohon123` | Warga pemohon pengajuan SKU |

> 🔒 *Pemberitahuan Keamanan: Seluruh akun bawaan di atas wajib diperbarui kredensialnya sebelum sistem di-deploy ke lingkungan produksi (production).*

---

## 10. Standar Kontribusi & Git Workflow

Proyek ini menerapkan kaidah rekayasa perangkat lunak standar industri:

### 10.1 Standar Pesan Commit (Conventional Commits)
Gunakan format standar berikut pada setiap commit:
```text
<type>(<scope>): <deskripsi singkat perubahan>

Contoh:
feat(auth): implementasi validasi NIK 16 digit pada form registrasi
fix(sku): perbaikan kalkulasi masa berlaku surat pada persetujuan lurah
docs(readme): perbarui arsitektur sistem dan matriks RBAC
refactor(filter): optimasi query pengecekan session role
```

### 10.2 Workflow Percabangan (Git Feature Branching)
1. Buat branch baru dari `main`:
   ```bash
   git checkout -b feat/nama-fitur-baru
   ```
2. Lakukan commit dengan pesan terstruktur.
3. Push branch ke remote repository dan buat *Pull Request (PR)* untuk proses *code review*.

---

## 11. Lisensi & Kontak

Proyek ini dilisensikan di bawah naungan **[MIT License](LICENSE)** - Anda bebas menggunakan, memodifikasi, dan mendistribusikan sistem ini untuk keperluan instansi publik maupun komersial.

- **Instansi**: Pemerintah Kalurahan Condongcatur, Kapanewon Depok, Kabupaten Sleman, D.I. Yogyakarta
- **Repository**: [Fatikasari1206/sistem-informasi-sku](https://github.com/Fatikasari1206/sistem-informasi-sku)
- **Lead Developer**: [@fatikasari1206](https://github.com/Fatikasari1206)
- **Status Proyek**: Aktif dalam pengembangan (*Active Development*)
