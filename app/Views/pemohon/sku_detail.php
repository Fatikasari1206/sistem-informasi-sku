<?= $this->extend('layouts/dashboard') ?>

<?php
$statusMeta = [
    'draft'                      => ['label' => 'Draft', 'class' => 'status-neutral', 'step' => 1],
    'diajukan'                   => ['label' => 'Menunggu Verifikasi Staf', 'class' => 'status-amber', 'step' => 1],
    'verifikasi_staf'            => ['label' => 'Sedang Diperiksa Staf', 'class' => 'status-indigo', 'step' => 2],
    'revisi'                     => ['label' => 'Perlu Revisi Berkas', 'class' => 'status-red', 'step' => 2],
    'menunggu_persetujuan_lurah' => ['label' => 'Menunggu TTE Lurah', 'class' => 'status-indigo', 'step' => 3],
    'disetujui'                  => ['label' => 'Disahkan Lurah', 'class' => 'status-green', 'step' => 4],
    'selesai'                    => ['label' => 'SKU Terbit', 'class' => 'status-green', 'step' => 4],
    'ditolak'                    => ['label' => 'Permohonan Ditolak', 'class' => 'status-red', 'step' => 2],
];

$dokLabelMap = [
    'pengantar_rt_rw' => 'Surat Pengantar RT/RW/Dukuh',
    'ktp'             => 'KTP Pemohon',
    'kk'              => 'Kartu Keluarga (KK)',
    'foto_usaha'      => 'Foto Tempat Usaha',
    'sku_lama'        => 'Lembar SKU Asli Lama',
];

$statusTempatMap = [
    'milik_sendiri' => 'Milik Sendiri',
    'sewa'          => 'Sewa / Kontrak',
    'menumpang'     => 'Menumpang',
    'lainnya'       => 'Lainnya',
];

$statusKey   = $permohonan['status'] ?? 'diajukan';
$currentMeta = $statusMeta[$statusKey] ?? ['label' => ucfirst($statusKey), 'class' => 'status-neutral', 'step' => 1];
$activeStep  = $currentMeta['step'];
$isIssue     = in_array($statusKey, ['revisi', 'ditolak'], true);

$steps = [
    1 => 'Pengajuan Dikirim',
    2 => 'Verifikasi Staf',
    3 => 'Pengesahan Lurah',
    4 => 'SKU Terbit',
];
?>

<?= $this->section('styles') ?>
<style>
    .detail-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 1.25rem 1.45rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eef2f6;
        margin-bottom: 1rem;
    }

    .detail-header h1 {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .detail-header p {
        font-size: 0.77rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        height: 36px;
        padding: 0 1.05rem;
        border-radius: 9999px;
        background: #f1f5f9;
        color: #334155;
        font-size: 0.77rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #cbd5e1;
        transition: background 0.15s ease;
    }

    .btn-back:hover {
        background: #e2e8f0;
    }

    .pill-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.28rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-amber   { background: #fef3c7; color: #b45309; }
    .status-green   { background: #dcfce7; color: #15803d; }
    .status-indigo  { background: #e0e7ff; color: #4338ca; }
    .status-red     { background: #fee2e2; color: #b91c1c; }
    .status-neutral { background: #f1f5f9; color: #475569; }

    /* Stepper */
    .stepper {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.65rem;
        margin-bottom: 1.1rem;
    }

    .step-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.7rem 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .step-item.is-done {
        background: #f0fdf4;
        border-color: #86efac;
    }

    .step-item.is-active {
        background: #eef2ff;
        border-color: #6366f1;
    }

    .step-item.is-issue {
        background: #fef2f2;
        border-color: #fca5a5;
    }

    .step-num {
        width: 24px;
        height: 24px;
        border-radius: 9999px;
        background: #cbd5e1;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .step-item.is-done .step-num   { background: #16a34a; }
    .step-item.is-active .step-num { background: #5246f0; }
    .step-item.is-issue .step-num  { background: #dc2626; }

    .step-label {
        font-size: 0.76rem;
        font-weight: 700;
        color: #1e293b;
    }

    .note-banner {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-size: 0.79rem;
        margin-bottom: 1rem;
        border: 1px solid #fca5a5;
        background: #fef2f2;
        color: #991b1b;
    }

    .note-banner strong {
        display: block;
        margin-bottom: 0.18rem;
    }

    /* 2-Column Content */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.15rem;
        align-items: start;
    }

    .sub-box {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.15rem;
        background: #ffffff;
    }

    .sub-box h2 {
        font-size: 0.76rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #334155;
        margin-bottom: 0.8rem;
    }

    .kv-grid {
        display: grid;
        grid-template-columns: 145px 1fr;
        gap: 0.5rem 0.75rem;
        font-size: 0.79rem;
    }

    .kv-k { color: #64748b; font-weight: 500; }
    .kv-v { color: #0f172a; font-weight: 600; }

    /* Timeline */
    .timeline {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .timeline-row {
        display: flex;
        gap: 0.7rem;
        align-items: flex-start;
        padding-bottom: 0.7rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .timeline-row:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 9999px;
        background: #5246f0;
        margin-top: 0.32rem;
        flex-shrink: 0;
    }

    .timeline-title {
        font-size: 0.79rem;
        font-weight: 700;
        color: #0f172a;
    }

    .timeline-meta {
        font-size: 0.71rem;
        color: #64748b;
        margin: 0.1rem 0 0.2rem;
    }

    .timeline-note {
        font-size: 0.76rem;
        color: #334155;
        line-height: 1.4;
    }

    /* Documents */
    .doc-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .doc-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.65rem;
        padding: 0.6rem 0.8rem;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
        font-size: 0.76rem;
    }

    .doc-link {
        border: none;
        background: transparent;
        color: #4f46e5;
        font-size: 0.76rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        white-space: nowrap;
        transition: background 0.15s ease;
    }

    .doc-link:hover {
        background: #eef2ff;
    }

    /* Popup Preview Modal */
    <?= view('pemohon/_doc_preview_modal', ['part' => 'styles'], ['saveData' => false]) ?>

    @media (max-width: 900px) {
        .stepper {
            grid-template-columns: repeat(2, 1fr);
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('pemohon/_sidebar_nav', ['activeMenu' => 'dashboard']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="detail-card">
        <div class="detail-header">
            <div>
                <h1><?= esc($permohonan['kode_pengajuan']) ?></h1>
                <p>
                    Layanan <?= ($permohonan['tipe_pengajuan'] ?? 'baru') === 'perpanjangan' ? 'Perpanjangan SKU' : 'SKU Baru' ?>
                    &bull; Diajukan <?= esc(format_tanggal_indo($permohonan['created_at'] ?? null, false, true)) ?>
                </p>
            </div>
            <div style="display: flex; align-items: center; gap: 0.55rem; flex-wrap: wrap;">
                <?php if (in_array($statusKey, ['disetujui', 'selesai'], true)): ?>
                    <a href="<?= base_url('pemohon/sku/cetak/' . (int) $permohonan['id']) ?>" target="_blank" class="btn-back" style="background: #10b981; color: #ffffff;">Cetak PDF SKU</a>
                <?php endif; ?>
                <a href="<?= base_url('pemohon/dashboard') ?>" class="btn-back">&larr; Kembali ke Dashboard</a>
            </div>
        </div>

        <!-- 4-Step Progress -->
        <div class="stepper" aria-label="Tahap Pemrosesan SKU">
            <?php foreach ($steps as $num => $stepTitle): ?>
                <?php
                    $stepClass = '';
                    if ($num < $activeStep) {
                        $stepClass = 'is-done';
                    } elseif ($num === $activeStep) {
                        $stepClass = $isIssue ? 'is-issue' : ($activeStep === 4 ? 'is-done' : 'is-active');
                    }
                ?>
                <div class="step-item <?= esc($stepClass) ?>">
                    <span class="step-num"><?= $num < $activeStep || ($num === 4 && $activeStep === 4) ? '&#10003;' : $num ?></span>
                    <span class="step-label"><?= esc($stepTitle) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($statusKey === 'revisi' && !empty($permohonan['catatan_revisi'])): ?>
            <div class="note-banner">
                <strong>Catatan Perbaikan dari Staf Kalurahan:</strong>
                <?= esc($permohonan['catatan_revisi']) ?>
            </div>
        <?php elseif ($statusKey === 'ditolak' && !empty($permohonan['catatan_penolakan'])): ?>
            <div class="note-banner">
                <strong>Alasan Penolakan:</strong>
                <?= esc($permohonan['catatan_penolakan']) ?>
            </div>
        <?php endif; ?>

        <div class="detail-grid">
            <!-- Data Pengajuan & Surat -->
            <div class="sub-box">
                <h2>Informasi Usaha &amp; Surat</h2>
                <div class="kv-grid">
                    <span class="kv-k">Nama Usaha</span>
                    <span class="kv-v"><?= esc($permohonan['nama_usaha']) ?></span>

                    <span class="kv-k">Jenis Usaha</span>
                    <span class="kv-v"><?= esc($permohonan['bidang_usaha']) ?></span>

                    <span class="kv-k">Alamat Usaha</span>
                    <span class="kv-v"><?= esc($permohonan['alamat_usaha']) ?></span>

                    <span class="kv-k">Padukuhan</span>
                    <span class="kv-v"><?= esc($permohonan['padukuhan'] ?? '-') ?></span>

                    <span class="kv-k">Lama Usaha</span>
                    <span class="kv-v"><?= (int) ($permohonan['lama_usaha_tahun'] ?? 0) ?> Tahun</span>

                    <span class="kv-k">Omset / Bulan</span>
                    <span class="kv-v">Rp <?= number_format((int) ($permohonan['omset_per_bulan'] ?? 0), 0, ',', '.') ?></span>

                    <span class="kv-k">Status Tempat</span>
                    <span class="kv-v"><?= esc($statusTempatMap[$permohonan['status_tempat_usaha'] ?? ''] ?? '-') ?></span>

                    <?php if (!empty($permohonan['nomor_surat'])): ?>
                        <span class="kv-k">Nomor Surat SKU</span>
                        <span class="kv-v"><?= esc($permohonan['nomor_surat']) ?></span>
                    <?php endif; ?>

                    <?php if (!empty($permohonan['berlaku_sampai'])): ?>
                        <span class="kv-k">Masa Berlaku</span>
                        <span class="kv-v"><?= esc(format_tanggal_indo($permohonan['tanggal_surat']) . ' s.d. ' . format_tanggal_indo($permohonan['berlaku_sampai'])) ?></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($dokumenList)): ?>
                    <div class="doc-list">
                        <h2 style="margin-bottom: 0.25rem;">Berkas Terunggah</h2>
                        <?php foreach ($dokumenList as $dok): ?>
                            <?php
                                $label   = $dokLabelMap[$dok['jenis_dokumen']] ?? ucfirst(str_replace('_', ' ', $dok['jenis_dokumen']));
                                $dokUrl  = base_url('pemohon/sku/dokumen/' . (int) $dok['id']);
                                $mime    = strtolower((string) ($dok['tipe_file'] ?? ''));
                                $isImage = strpos($mime, 'image/') === 0 || preg_match('/\.(jpe?g|png|webp)$/i', (string) $dok['nama_file']);
                            ?>
                            <div class="doc-row">
                                <div>
                                    <div style="font-weight: 700; color: #0f172a;"><?= esc($label) ?></div>
                                    <div style="font-size: 0.7rem; color: #64748b;"><?= esc($dok['nama_file']) ?></div>
                                </div>
                                <button
                                    type="button"
                                    class="doc-link"
                                    data-doc-preview
                                    data-url="<?= esc($dokUrl) ?>"
                                    data-title="<?= esc($label . ' — ' . $dok['nama_file']) ?>"
                                    data-kind="<?= $isImage ? 'image' : 'pdf' ?>"
                                >Lihat</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Riwayat Log Status -->
            <div class="sub-box">
                <h2>Riwayat Status Pengajuan</h2>
                <?php if (empty($timeline)): ?>
                    <p style="font-size: 0.78rem; color: #64748b;">Belum ada catatan riwayat status.</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($timeline as $log): ?>
                            <?php $logMeta = $statusMeta[$log['status_baru']] ?? ['label' => ucfirst($log['status_baru']), 'class' => 'status-neutral']; ?>
                            <div class="timeline-row">
                                <span class="timeline-dot"></span>
                                <div>
                                    <div class="timeline-title"><?= esc($logMeta['label']) ?></div>
                                    <div class="timeline-meta">
                                        <?= esc(format_tanggal_indo($log['created_at'] ?? null, false, true)) ?>
                                        <?= !empty($log['nama_user']) ? ' &bull; ' . esc($log['nama_user']) : '' ?>
                                    </div>
                                    <?php if (!empty($log['keterangan'])): ?>
                                        <div class="timeline-note"><?= esc($log['keterangan']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= view('pemohon/_doc_preview_modal', ['part' => 'body']) ?>
<?= $this->endSection() ?>
