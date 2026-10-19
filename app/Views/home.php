<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section dengan Foto & Logo -->
<div class="hero-wrapper">
    <div class="hero-content">
        <!-- Badge Kalurahan Resmi -->
        <div class="official-pill">
            <img src="<?= base_url('images/logo-kalurahan.svg') ?>" alt="Logo Kalurahan" class="pill-logo">
            <span>Pelayanan Resmi Pemerintah Kalurahan <?= esc($pengaturan['nama_kalurahan'] ?? 'Condongcatur') ?></span>
        </div>
        
        <!-- Judul Utama -->
        <h1 class="hero-title">
            Pelayanan Pembuatan &amp; Perpanjangan
            <span class="text-gradient">Surat Keterangan Usaha</span>
        </h1>

        <!-- Deskripsi -->
        <p class="hero-description">
            Layanan Pembuatan & Perpanjangan SKU Digital Kalurahan Condongcatur.
        </p>

        <!-- Tombol Aksi (CTA) -->
        <div class="hero-actions">
            <a href="<?= base_url('register') ?>" class="btn btn-primary btn-hero-main" id="btn-hero-daftar">
                <i class="fa-solid fa-file-circle-plus"></i> Ajukan Permohonan SKU
            </a>
            <a href="<?= base_url('login') ?>" class="btn btn-secondary btn-hero-sub" id="btn-hero-login">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Akun
            </a>
        </div>

        <!-- Keunggulan Layanan -->
        <div class="hero-features">
            <div class="feature-item">
                <i class="fa-solid fa-bolt text-warning"></i>
                <span>Verifikasi Cepat</span>
            </div>
            <div class="feature-item">
                <i class="fa-solid fa-shield-halved text-primary"></i>
                <span>QR Code Autentikasi</span>
            </div>
            <div class="feature-item">
                <i class="fa-solid fa-circle-check text-success"></i>
                <span>100% Bebas Biaya</span>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Foto Kantor & Visual Card -->
    <div class="hero-visual">
        <div class="visual-card">
            <div class="photo-container">
                <img src="<?= base_url('images/kantor-pelayanan.jpg') ?>" alt="Kantor Pelayanan Kalurahan Condongcatur" class="hero-photo">
                <div class="photo-overlay"></div>
            </div>

            <!-- Floating Badge Atas: Logo & Nama Kalurahan -->
            <div class="floating-badge-top">
                <img src="<?= base_url('images/logo-kalurahan.svg') ?>" alt="Emblem Kalurahan" class="badge-logo-img">
                <div class="badge-text">
                    <strong>Pemerintah Kalurahan <?= esc($pengaturan['nama_kalurahan'] ?? 'Condongcatur') ?></strong>
                    <span>Kapanewon <?= esc($pengaturan['nama_kapanewon'] ?? 'Depok') ?>, <?= esc($pengaturan['kabupaten_kota'] ?? 'Sleman') ?></span>
                </div>
            </div>

            <!-- Floating Badge Bawah: Info Layanan & Jam Operasional -->
            <div class="floating-badge-bottom">
                <div class="status-live">
                    <span class="pulse-dot"></span>
                    <span class="status-label">Layanan Online Aktif</span>
                </div>
                <div class="operational-hours">
                    <i class="fa-regular fa-clock"></i> Pelayanan Verifikasi: Senin - Jumat (08.00 - 15.30 WIB)
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alur 4 Langkah Pengajuan -->
<div style="margin-bottom: 3.5rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.75rem; font-weight: 800; color: var(--secondary); letter-spacing: -0.02em;">Alur Pelayanan Digital</h2>
        <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 0.25rem;">Empat tahapan mudah dari pengajuan sampai surat resmi terbit</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1.25rem;">
        <div class="card step-card" style="text-align: center; padding: 2rem 1.25rem;">
            <div style="width: 56px; height: 56px; background: var(--primary-light); color: var(--primary); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--primary); text-transform: uppercase; margin-bottom: 0.25rem;">Langkah 1</div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">Isi Data Usaha</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Pemohon mengisi data identitas usaha dan mengunggah berkas syarat yang dibutuhkan.</p>
        </div>

        <div class="card step-card" style="text-align: center; padding: 2rem 1.25rem;">
            <div style="width: 56px; height: 56px; background: var(--warning-light); color: var(--warning); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div style="font-size: 0.75rem; font-weight: 800; color: #b45309; text-transform: uppercase; margin-bottom: 0.25rem;">Langkah 2</div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">Verifikasi Staf</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Staf Kalurahan memeriksa keabsahan dokumen dan memberikan catatan jika perlu revisi.</p>
        </div>

        <div class="card step-card" style="text-align: center; padding: 2rem 1.25rem;">
            <div style="width: 56px; height: 56px; background: var(--purple-light); color: var(--purple); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                <i class="fa-solid fa-signature"></i>
            </div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--purple); text-transform: uppercase; margin-bottom: 0.25rem;">Langkah 3</div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">Persetujuan Lurah</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Lurah mengesahkan surat secara digital dengan nomor resmi dan QR Code autentikasi.</p>
        </div>

        <div class="card step-card" style="text-align: center; padding: 2rem 1.25rem;">
            <div style="width: 56px; height: 56px; background: var(--success-light); color: var(--success); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem;">
                <i class="fa-solid fa-print"></i>
            </div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--success); text-transform: uppercase; margin-bottom: 0.25rem;">Langkah 4</div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.5rem;">Cetak / Unduh SKU</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted);">Surat resmi langsung dapat diunduh dan dicetak sendiri kapan saja oleh pemohon.</p>
        </div>
    </div>
</div>

<!-- Persyaratan Berkas -->
<div class="card" style="padding: 2.25rem; margin-bottom: 2.5rem;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
        <div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--secondary); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-folder-closed text-primary"></i> Berkas Persyaratan SKU Baru
            </h3>
            <ul style="list-style: none; padding: 0; font-size: 0.92rem; color: var(--text-main); display: flex; flex-direction: column; gap: 0.75rem;">
                <li style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check text-success"></i> Scan / Foto KTP Pemohon (Asli)</li>
                <li style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check text-success"></i> Scan / Foto Kartu Keluarga (KK)</li>
                <li style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check text-success"></i> Surat Pengantar dari RT / RW setempat</li>
                <li style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check text-success"></i> Foto tempat / aktivitas usaha yang dijalankan</li>
            </ul>
        </div>

        <div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--secondary); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-arrows-rotate text-purple"></i> Berkas Perpanjangan SKU
            </h3>
            <ul style="list-style: none; padding: 0; font-size: 0.92rem; color: var(--text-main); display: flex; flex-direction: column; gap: 0.75rem;">
                <li style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check text-purple"></i> Surat Keterangan Usaha (SKU) Lama</li>
                <li style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check text-purple"></i> Scan / Foto KTP & KK Pemohon</li>
                <li style="display: flex; align-items: center; gap: 0.5rem;"><i class="fa-solid fa-circle-check text-purple"></i> Foto terbaru tempat / aktivitas usaha</li>
            </ul>
        </div>
    </div>
</div>

<!-- Informasi Kontak & Kalurahan -->
<div class="card info-kalurahan-card">
    <div class="info-grid">
        <div class="info-brand">
            <img src="<?= base_url('images/logo-kalurahan.svg') ?>" alt="Logo Kalurahan" class="info-logo">
            <div>
                <h4>Kantor Kalurahan <?= esc($pengaturan['nama_kalurahan'] ?? 'Condongcatur') ?></h4>
                <p>Kapanewon <?= esc($pengaturan['nama_kapanewon'] ?? 'Depok') ?>, <?= esc($pengaturan['kabupaten_kota'] ?? 'Kabupaten Sleman') ?>, <?= esc($pengaturan['provinsi'] ?? 'D.I. Yogyakarta') ?></p>
            </div>
        </div>
        <div class="info-meta">
            <?php if (!empty($pengaturan['telepon'])): ?>
                <div><i class="fa-solid fa-phone text-primary"></i> <?= esc($pengaturan['telepon']) ?></div>
            <?php endif; ?>
            <?php if (!empty($pengaturan['email'])): ?>
                <div><i class="fa-solid fa-envelope text-primary"></i> <?= esc($pengaturan['email']) ?></div>
            <?php endif; ?>
            <?php if (!empty($pengaturan['alamat_kantor'])): ?>
                <div><i class="fa-solid fa-location-dot text-danger"></i> <?= esc($pengaturan['alamat_kantor']) ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* CSS Tambahan Khusus Hero Section Berfoto & Berlogo */
.hero-wrapper {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 2.75rem;
    align-items: center;
    padding: 2rem 0 3.5rem;
}

.official-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
    background: var(--primary-light);
    color: var(--primary);
    padding: 0.45rem 1.1rem;
    border-radius: 9999px;
    font-size: 0.82rem;
    font-weight: 700;
    margin-bottom: 1.25rem;
    border: 1px solid rgba(37,99,235,0.2);
    box-shadow: 0 2px 6px rgba(37,99,235,0.08);
}

.pill-logo {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

.hero-title {
    font-size: 2.65rem;
    font-weight: 800;
    color: var(--secondary);
    line-height: 1.2;
    letter-spacing: -0.03em;
    margin-bottom: 1.25rem;
}

.text-gradient {
    display: block;
    background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-description {
    font-size: 1.05rem;
    color: var(--text-muted);
    line-height: 1.65;
    margin-bottom: 2rem;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.btn-hero-main {
    padding: 0.85rem 1.85rem;
    font-size: 0.98rem;
    border-radius: var(--radius-md);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
}

.btn-hero-sub {
    padding: 0.85rem 1.75rem;
    font-size: 0.98rem;
    border-radius: var(--radius-md);
}

.hero-features {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px dashed var(--border);
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--secondary);
}

/* Kolom Kanan: Card Foto & Overlay Badge */
.hero-visual {
    position: relative;
}

.visual-card {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
    border: 1px solid rgba(226, 232, 240, 0.8);
    background: #ffffff;
    transition: var(--transition);
}

.visual-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 24px 50px rgba(15, 23, 42, 0.16);
}

.photo-container {
    position: relative;
    width: 100%;
    height: 380px;
    overflow: hidden;
}

.hero-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.visual-card:hover .hero-photo {
    transform: scale(1.04);
}

.photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15, 23, 42, 0.25) 0%, rgba(15, 23, 42, 0.65) 100%);
}

.floating-badge-top {
    position: absolute;
    top: 1.25rem;
    left: 1.25rem;
    right: 1.25rem;
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1rem;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    border: 1px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.badge-logo-img {
    width: 38px;
    height: 38px;
    object-fit: contain;
    flex-shrink: 0;
}

.badge-text strong {
    display: block;
    font-size: 0.88rem;
    color: var(--secondary);
    font-weight: 700;
}

.badge-text span {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.floating-badge-bottom {
    position: absolute;
    bottom: 1.25rem;
    left: 1.25rem;
    right: 1.25rem;
    background: rgba(15, 23, 42, 0.85);
    backdrop-filter: blur(10px);
    padding: 0.85rem 1.15rem;
    border-radius: 16px;
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.status-live {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.82rem;
    font-weight: 700;
    margin-bottom: 0.35rem;
    color: #34d399;
}

.pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulse 1.8s infinite;
}

@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.operational-hours {
    font-size: 0.75rem;
    color: #94a3b8;
}

.step-card {
    transition: var(--transition);
}
.step-card:hover {
    transform: translateY(-4px);
}

/* Info Kalurahan Footer Card */
.info-kalurahan-card {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #ffffff 0%, var(--primary-light) 100%);
}

.info-grid {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.info-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-logo {
    width: 48px;
    height: 48px;
    object-fit: contain;
}

.info-brand h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: 0.15rem;
}

.info-brand p {
    font-size: 0.82rem;
    color: var(--text-muted);
}

.info-meta {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    font-size: 0.82rem;
    color: var(--text-main);
}

.info-meta div {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

/* Responsiveness */
@media (max-width: 960px) {
    .hero-wrapper {
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 1rem 0 2.5rem;
    }
    .hero-title {
        font-size: 2.15rem;
    }
    .photo-container {
        height: 280px;
    }
}
</style>
<?= $this->endSection() ?>
