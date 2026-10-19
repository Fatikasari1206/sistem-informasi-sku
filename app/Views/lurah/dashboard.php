<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--secondary); letter-spacing: -0.02em;">
                Dashboard Persetujuan Lurah
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 0.25rem;">
                Otorisasi pengesahan dan penandatanganan Surat Keterangan Usaha yang telah lolos verifikasi staf.
            </p>
        </div>
        <div>
            <span class="badge" style="background: var(--purple-light); color: var(--purple); font-size: 0.85rem; padding: 0.5rem 1rem;">
                <i class="fa-solid fa-signature"></i> Pejabat Penandatangan: <?= esc(session()->get('namaLengkap')) ?>
            </span>
        </div>
    </div>
</div>

<!-- Stat Grid -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-stamp"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['menunggu_approval'] ?></h3>
            <p>Menunggu Tanda Tangan</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['disetujui'] ?></h3>
            <p>Telah Disetujui</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-certificate"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['total_terbit'] ?></h3>
            <p>Total SKU Resmi Diterbitkan</p>
        </div>
    </div>
</div>

<!-- Antrean TTD Lurah -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--secondary); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-file-signature text-purple"></i> Antrean Surat Siap Disahkan
        </h3>
    </div>

    <?php if (empty($menunggu)): ?>
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <div style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 0.75rem;"><i class="fa-solid fa-award"></i></div>
            <p style="font-size: 0.95rem; font-weight: 600;">Tidak ada surat yang menunggu tanda tangan saat ini.</p>
            <span style="font-size: 0.82rem;">Semua berkas yang telah diverifikasi staf telah ditandatangani.</span>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Kode & Tanggal</th>
                        <th>Pemohon</th>
                        <th>Nama & Bidang Usaha</th>
                        <th>Nomor Draft Surat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menunggu as $row): ?>
                        <tr>
                            <td>
                                <strong><?= esc($row['kode_pengajuan']) ?></strong><br>
                                <span style="font-size: 0.75rem; color: var(--text-muted);"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                            </td>
                            <td>
                                <strong><?= esc($row['nama_pemohon']) ?></strong><br>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">NIK: <?= esc($row['nik_pemohon']) ?></span>
                            </td>
                            <td>
                                <strong><?= esc($row['nama_usaha']) ?></strong><br>
                                <span style="font-size: 0.75rem; color: var(--text-muted);"><?= esc($row['bidang_usaha']) ?></span>
                            </td>
                            <td>
                                <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;"><?= esc($row['nomor_surat'] ?? 'Otomatis digenerate') ?></code>
                            </td>
                            <td>
                                <span class="badge badge-menunggu_persetujuan_lurah">
                                    Siap TTD
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('lurah/review/' . $row['id']) ?>" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, var(--purple) 0%, #6d28d9 100%);">
                                    <i class="fa-solid fa-signature"></i> Tinjau & Sahkan
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
