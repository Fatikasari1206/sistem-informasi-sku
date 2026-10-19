<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: var(--secondary); letter-spacing: -0.02em;">
                Halo, <?= esc(session()->get('namaLengkap')) ?>! 👋
            </h1>
            <p style="color: var(--text-muted); font-size: 0.92rem; margin-top: 0.25rem;">
                Selamat datang di portal permohonan Surat Keterangan Usaha (SKU) Kalurahan Condongcatur.
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="<?= base_url('pemohon/sku/baru') ?>" class="btn btn-primary" id="btn-ajukan-baru">
                <i class="fa-solid fa-plus-circle"></i> Ajukan SKU Baru
            </a>
            <a href="<?= base_url('pemohon/sku/perpanjangan') ?>" class="btn btn-secondary" id="btn-ajukan-perpanjangan">
                <i class="fa-solid fa-arrows-rotate"></i> Perpanjangan SKU
            </a>
        </div>
    </div>
</div>

<!-- Stat Grid -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-folder-open"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['total_pengajuan'] ?></h3>
            <p>Total Pengajuan Anda</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon yellow">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['sedang_proses'] ?></h3>
            <p>Sedang Diproses</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['perlu_revisi'] ?></h3>
            <p>Perlu Perbaikan Berkas</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-meta">
            <h3><?= $stats['selesai'] ?></h3>
            <p>SKU Resmi Terbit</p>
        </div>
    </div>
</div>

<!-- Riwayat Pengajuan Pemohon -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--secondary); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-clock-rotate-left text-primary"></i> Daftar Pengajuan Surat Anda
        </h3>
    </div>

    <?php if (empty($permohonanList)): ?>
        <div style="text-align: center; padding: 3.5rem 1rem; color: var(--text-muted);">
            <div style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"><i class="fa-solid fa-file-invoice"></i></div>
            <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--secondary); margin-bottom: 0.35rem;">Belum Ada Permohonan SKU</h4>
            <p style="font-size: 0.88rem; max-width: 420px; margin: 0 auto 1.5rem;">Mulai ajukan Surat Keterangan Usaha secara online untuk kemudahan izin, perbankan, dan pengembangan usaha Anda.</p>
            <a href="<?= base_url('pemohon/sku/baru') ?>" class="btn btn-primary">
                <i class="fa-solid fa-plus-circle"></i> Buat Pengajuan Pertama Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Kode & Tanggal</th>
                        <th>Tipe</th>
                        <th>Nama Usaha</th>
                        <th>Nomor Surat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permohonanList as $row): ?>
                        <tr>
                            <td>
                                <strong><?= esc($row['kode_pengajuan']) ?></strong><br>
                                <span style="font-size: 0.75rem; color: var(--text-muted);"><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: var(--text-main);">
                                    <?= ucfirst(esc($row['tipe_pengajuan'])) ?>
                                </span>
                            </td>
                            <td>
                                <strong><?= esc($row['nama_usaha']) ?></strong><br>
                                <span style="font-size: 0.75rem; color: var(--text-muted);"><?= esc($row['bidang_usaha']) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($row['nomor_surat'])): ?>
                                    <strong style="color: var(--primary);"><?= esc($row['nomor_surat']) ?></strong>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">Menunggu penerbitan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= esc($row['status']) ?>">
                                    <?= str_replace('_', ' ', esc($row['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.4rem;">
                                    <?php if (in_array($row['status'], ['disetujui', 'selesai'])): ?>
                                        <a href="<?= base_url('pemohon/sku/cetak/' . $row['id']) ?>" target="_blank" class="btn btn-primary btn-sm" style="background: var(--success);">
                                            <i class="fa-solid fa-print"></i> Cetak SKU
                                        </a>
                                    <?php elseif ($row['status'] === 'revisi'): ?>
                                        <a href="<?= base_url('pemohon/sku/perbaiki/' . $row['id']) ?>" class="btn btn-danger-soft btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Perbaiki Berkas
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('pemohon/sku/detail/' . $row['id']) ?>" class="btn btn-secondary btn-sm">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
