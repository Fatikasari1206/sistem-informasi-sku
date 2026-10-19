<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--secondary); letter-spacing: -0.02em;">
                Dashboard Administrator
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 0.25rem;">
                Kelola data pengguna, konfigurasi instansi Kalurahan, dan pantau aktivitas sistem SKU.
            </p>
        </div>
        <div>
            <span class="badge" style="background: #e2e8f0; color: var(--secondary); font-size: 0.85rem; padding: 0.5rem 1rem;">
                <i class="fa-solid fa-building-columns"></i> <?= esc($pengaturan['nama_kalurahan']) ?> - <?= esc($pengaturan['kabupaten_kota']) ?>
            </span>
        </div>
    </div>
</div>

<!-- Stat Grid -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['total_users'] ?></h3>
            <p>Total Pengguna</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-address-card"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['total_pemohon'] ?></h3>
            <p>Warga / Pemohon Terdaftar</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-user-tie"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['total_staf'] ?></h3>
            <p>Staf Pelayanan</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="fa-solid fa-file-signature"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['total_sku'] ?></h3>
            <p>Total Pengajuan SKU</p>
        </div>
    </div>
</div>

<!-- Status Pengajuan Ringkasan -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--secondary); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-chart-pie text-primary"></i> Status Alur Permohonan SKU
        </h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
            <div style="padding: 1rem; background: var(--warning-light); border: 1px solid #fde68a; border-radius: var(--radius-md);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #b45309; text-transform: uppercase;">Pengajuan Baru</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #92400e; margin-top: 0.25rem;"><?= $stats['sku_diajukan'] ?></div>
                <div style="font-size: 0.75rem; color: #b45309;">Menunggu diperiksa staf</div>
            </div>

            <div style="padding: 1rem; background: var(--primary-light); border: 1px solid #bfdbfe; border-radius: var(--radius-md);">
                <div style="font-size: 0.78rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">Dalam Verifikasi</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #1e40af; margin-top: 0.25rem;"><?= $stats['sku_verifikasi'] ?></div>
                <div style="font-size: 0.75rem; color: var(--primary);">Sedang diperiksa berkasnya</div>
            </div>

            <div style="padding: 1rem; background: var(--purple-light); border: 1px solid #ddd6fe; border-radius: var(--radius-md);">
                <div style="font-size: 0.78rem; font-weight: 700; color: var(--purple); text-transform: uppercase;">Menunggu Lurah</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #5b21b6; margin-top: 0.25rem;"><?= $stats['sku_menunggu'] ?></div>
                <div style="font-size: 0.75rem; color: var(--purple);">Menunggu tanda tangan</div>
            </div>

            <div style="padding: 1rem; background: var(--success-light); border: 1px solid #a7f3d0; border-radius: var(--radius-md);">
                <div style="font-size: 0.78rem; font-weight: 700; color: var(--success); text-transform: uppercase;">Selesai Terbit</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: #065f46; margin-top: 0.25rem;"><?= $stats['sku_selesai'] ?></div>
                <div style="font-size: 0.75rem; color: var(--success);">Surat SKU diterbitkan</div>
            </div>
        </div>
    </div>

    <!-- Profil Kalurahan Quick View -->
    <div class="card">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--secondary); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-landmark text-primary"></i> Profil Instansi
        </h3>
        
        <div style="font-size: 0.88rem; color: var(--text-main);">
            <p style="margin-bottom: 0.6rem;"><strong>Kalurahan:</strong> <?= esc($pengaturan['nama_kalurahan']) ?></p>
            <p style="margin-bottom: 0.6rem;"><strong>Kapanewon:</strong> <?= esc($pengaturan['nama_kapanewon']) ?></p>
            <p style="margin-bottom: 0.6rem;"><strong>Lurah:</strong> <?= esc($pengaturan['nama_lurah']) ?></p>
            <p style="margin-bottom: 0.6rem;"><strong>NIP:</strong> <?= esc($pengaturan['nip_lurah'] ?? '-') ?></p>
            <p style="margin-bottom: 0.6rem;"><strong>Format No. Surat:</strong> <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;"><?= esc($pengaturan['format_nomor_surat']) ?></code></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
