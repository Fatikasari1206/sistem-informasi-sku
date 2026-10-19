<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--secondary); letter-spacing: -0.02em;">
                Dashboard Pelayanan Staf
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 0.25rem;">
                Verifikasi berkas persyaratan permohonan SKU baru dan perpanjangan dari warga.
            </p>
        </div>
        <div>
            <span class="badge" style="background: var(--primary-light); color: var(--primary); font-size: 0.85rem; padding: 0.5rem 1rem;">
                <i class="fa-solid fa-user-check"></i> Petugas Verifikator: <?= esc(session()->get('namaLengkap')) ?>
            </span>
        </div>
    </div>
</div>

<!-- Stat Grid -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="fa-solid fa-inbox"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['total_masuk'] ?></h3>
            <p>Antrean Berkas Masuk</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['perlu_revisi'] ?></h3>
            <p>Perlu Revisi Pemohon</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-paper-plane"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['diteruskan'] ?></h3>
            <p>Diteruskan ke Lurah</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['selesai'] ?></h3>
            <p>SKU Selesai Terbit</p>
        </div>
    </div>
</div>

<!-- Antrean Masuk -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--secondary); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-clock-rotate-left text-primary"></i> Antrean Permohonan Terbaru
        </h3>
    </div>

    <?php if (empty($antrean)): ?>
        <div style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
            <div style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 0.75rem;"><i class="fa-solid fa-folder-open"></i></div>
            <p style="font-size: 0.95rem; font-weight: 600;">Belum ada antrean berkas yang menunggu verifikasi.</p>
            <span style="font-size: 0.82rem;">Semua berkas pengajuan sudah ditindaklanjuti.</span>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Pemohon</th>
                        <th>Nama Usaha</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($antrean as $row): ?>
                        <tr>
                            <td><strong><?= esc($row['kode_pengajuan']) ?></strong></td>
                            <td>
                                <div><strong><?= esc($row['nama_pemohon']) ?></strong></div>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">NIK: <?= esc($row['nik_pemohon']) ?></span>
                            </td>
                            <td><?= esc($row['nama_usaha']) ?></td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: var(--text-main);">
                                    <?= ucfirst(esc($row['tipe_pengajuan'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?= esc($row['status']) ?>">
                                    <?= str_replace('_', ' ', esc($row['status'])) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('staf/verifikasi/' . $row['id']) ?>" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-file-circle-check"></i> Verifikasi
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
