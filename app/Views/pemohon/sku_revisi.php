<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar_nav') ?>
<?= $this->include('pemohon/_sidebar_nav') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<?= $this->include('pemohon/_sku_form_styles') ?>
<style>
    .revisi-stack {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .revisi-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.45rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
    }

    .revisi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .revisi-title-row {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
    }

    .revisi-title-row h1 {
        font-size: 1.08rem;
        font-weight: 700;
        color: #0f172a;
    }

    .revisi-sub {
        font-size: 0.74rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    .status-pill-revisi {
        display: inline-block;
        padding: 0.22rem 0.65rem;
        border-radius: 9999px;
        font-size: 0.71rem;
        font-weight: 700;
        background: #fee2e2;
        color: #b91c1c;
    }

    .btn-back-dash {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.48rem 0.95rem;
        border-radius: 9999px;
        background: #f1f5f9;
        color: #334155;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-back-dash:hover {
        background: #e2e8f0;
    }

    .stepper {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.7rem;
    }

    .step-box {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.65rem 0.75rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .step-box.is-done {
        border-color: #bbf7d0;
        background: #f0fdf4;
    }

    .step-box.is-issue {
        border-color: #fecaca;
        background: #fef2f2;
    }

    .step-num {
        width: 24px;
        height: 24px;
        border-radius: 9999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.71rem;
        font-weight: 700;
        background: #cbd5e1;
        color: #475569;
        flex-shrink: 0;
    }

    .step-box.is-done .step-num {
        background: #16a34a;
        color: #ffffff;
    }

    .step-box.is-issue .step-num {
        background: #dc2626;
        color: #ffffff;
    }

    .step-label {
        font-size: 0.73rem;
        font-weight: 600;
        color: #475569;
    }

    .step-box.is-done .step-label {
        color: #166534;
    }

    .step-box.is-issue .step-label {
        color: #b91c1c;
    }

    .note-banner-revisi {
        margin-top: 0.9rem;
        padding: 0.8rem 0.95rem;
        border-radius: 10px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 0.78rem;
        line-height: 1.5;
    }

    .note-banner-revisi strong {
        display: block;
        font-size: 0.74rem;
        margin-bottom: 0.2rem;
        color: #7f1d1d;
    }

    .revisi-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 1.35rem;
        align-items: start;
    }

    .revisi-subnote {
        font-size: 0.72rem;
        color: #64748b;
        margin: -0.25rem 0 0.8rem;
    }

    .revisi-doc-block {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.72rem 0.85rem;
        background: #ffffff;
    }

    .revisi-doc-current {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.55rem;
    }

    .revisi-doc-title {
        font-size: 0.76rem;
        font-weight: 700;
        color: #0f172a;
    }

    .revisi-doc-filename {
        font-size: 0.71rem;
        color: #64748b;
        margin-top: 0.1rem;
        word-break: break-word;
    }

    .btn-doc-view {
        border: none;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.34rem 0.75rem;
        border-radius: 9999px;
        cursor: pointer;
        flex-shrink: 0;
        transition: background-color 0.15s ease;
    }

    .btn-doc-view:hover {
        background: #e0e7ff;
    }

    .revisi-doc-block .dropzone-card {
        padding: 0.52rem 0.72rem;
    }

    <?= view('pemohon/_doc_preview_modal', ['part' => 'styles'], ['saveData' => false]) ?>

    @media (max-width: 900px) {
        .stepper {
            grid-template-columns: repeat(2, 1fr);
        }

        .revisi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$errors    = session()->getFlashdata('errors') ?? [];
$errorMsg  = session()->getFlashdata('error');
$isBaru    = ($permohonan['tipe_pengajuan'] ?? 'baru') === 'baru';
$statusMap = [
    'milik_sendiri' => 'Milik Sendiri',
    'sewa'          => 'Sewa / Kontrak',
    'menumpang'     => 'Menumpang / Lahan Keluarga',
    'lainnya'       => 'Lainnya',
];
$selectedPadukuhan = old('padukuhan_usaha', (string) ($permohonan['padukuhan_usaha'] ?? ''));
$selectedStatus    = old('status_tempat_usaha', (string) ($permohonan['status_tempat_usaha'] ?? ''));
$omsetRaw          = old('omset_per_bulan', (string) ($permohonan['omset_per_bulan'] ?? '0'));
$omsetDigits       = preg_replace('/\D/', '', (string) $omsetRaw);
$omsetFormatted    = $omsetDigits !== '' ? number_format((int) $omsetDigits, 0, ',', '.') : '';
?>

<div class="revisi-stack">
    <div class="revisi-card">
        <div class="revisi-header">
            <div>
                <div class="revisi-title-row">
                    <h1><?= esc($permohonan['kode_pengajuan']) ?></h1>
                    <span class="status-pill-revisi">Perlu Revisi</span>
                </div>
                <div class="revisi-sub">
                    Layanan <?= $isBaru ? 'SKU Baru' : 'Perpanjangan SKU' ?>
                    &bull; Diajukan <?= date('d M Y H:i', strtotime($permohonan['created_at'])) ?>
                </div>
            </div>
            <a href="<?= base_url('pemohon/dashboard') ?>" class="btn-back-dash">&larr; Kembali ke Dashboard</a>
        </div>

        <div class="stepper">
            <div class="step-box is-done">
                <span class="step-num">&#10003;</span>
                <span class="step-label">Pengajuan Dikirim</span>
            </div>
            <div class="step-box is-issue">
                <span class="step-num">2</span>
                <span class="step-label">Perbaikan Pemohon</span>
            </div>
            <div class="step-box">
                <span class="step-num">3</span>
                <span class="step-label">Pengesahan Lurah</span>
            </div>
            <div class="step-box">
                <span class="step-num">4</span>
                <span class="step-label">SKU Terbit</span>
            </div>
        </div>

        <div class="note-banner-revisi">
            <strong>Catatan Revisi dari Staf Pelayanan</strong>
            <?= nl2br(esc($permohonan['catatan_revisi'] ?: 'Silakan periksa kembali kelengkapan data dan dokumen pengajuan Anda.')) ?>
        </div>
    </div>

    <div class="revisi-card">
        <?php if (!empty($errorMsg)): ?>
            <div class="sku-form-alert"><?= esc($errorMsg) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="sku-form-alert">
                <ul>
                    <?php foreach ($errors as $msg): ?>
                        <li><?= esc($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('pemohon/sku/perbaiki/' . $permohonan['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="revisi-grid">
                <div>
                    <div class="sku-section-title">PERBAIKI DATA USAHA</div>

                    <?php if ($isBaru): ?>
                        <div class="sku-field">
                            <label for="nama_usaha">Nama Usaha</label>
                            <input type="text" id="nama_usaha" name="nama_usaha" value="<?= esc(old('nama_usaha', $permohonan['nama_usaha'])) ?>" required>
                        </div>

                        <div class="sku-field">
                            <label for="bidang_usaha">Jenis / Bidang Usaha</label>
                            <input type="text" id="bidang_usaha" name="bidang_usaha" value="<?= esc(old('bidang_usaha', $permohonan['bidang_usaha'])) ?>" required>
                        </div>

                        <div class="sku-field">
                            <label for="alamat_usaha">Alamat Usaha</label>
                            <input type="text" id="alamat_usaha" name="alamat_usaha" value="<?= esc(old('alamat_usaha', $permohonan['alamat_usaha'])) ?>" required>
                        </div>

                        <div class="sku-field">
                            <label for="padukuhan_usaha">Padukuhan</label>
                            <select id="padukuhan_usaha" name="padukuhan_usaha" required>
                                <option value="">Pilih padukuhan</option>
                                <?php foreach ($padukuhanList as $dukuh): ?>
                                    <option value="<?= esc($dukuh) ?>" <?= $selectedPadukuhan === $dukuh ? 'selected' : '' ?>>
                                        Padukuhan <?= esc($dukuh) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="sku-subgrid-2">
                            <div class="sku-field">
                                <label for="lama_usaha_tahun">Lama Usaha (Tahun)</label>
                                <input type="number" id="lama_usaha_tahun" name="lama_usaha_tahun" min="0" max="99" value="<?= esc(old('lama_usaha_tahun', (string) $permohonan['lama_usaha_tahun'])) ?>" required>
                            </div>
                            <div class="sku-field">
                                <label for="omset_per_bulan">Omset / Bulan (Rp)</label>
                                <input type="text" id="omset_per_bulan" name="omset_per_bulan" class="input-currency" inputmode="numeric" value="<?= esc($omsetFormatted) ?>" required>
                            </div>
                        </div>

                        <div class="sku-field">
                            <label for="status_tempat_usaha">Status Tempat Usaha</label>
                            <select id="status_tempat_usaha" name="status_tempat_usaha" required>
                                <option value="">Pilih status tempat</option>
                                <?php foreach ($statusMap as $val => $label): ?>
                                    <option value="<?= esc($val) ?>" <?= $selectedStatus === $val ? 'selected' : '' ?>>
                                        <?= esc($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="sku-field">
                            <label for="readonly_nama_usaha">Nama Usaha</label>
                            <input type="text" id="readonly_nama_usaha" value="<?= esc($permohonan['nama_usaha']) ?>" readonly>
                        </div>

                        <div class="sku-field">
                            <label for="readonly_bidang_usaha">Jenis / Bidang Usaha</label>
                            <input type="text" id="readonly_bidang_usaha" value="<?= esc($permohonan['bidang_usaha']) ?>" readonly>
                        </div>

                        <div class="sku-field">
                            <label for="alamat_usaha">Alamat Usaha</label>
                            <input type="text" id="alamat_usaha" name="alamat_usaha" value="<?= esc(old('alamat_usaha', $permohonan['alamat_usaha'])) ?>" required>
                        </div>

                        <div class="sku-field">
                            <label for="omset_per_bulan">Perkiraan Omset / Bulan (Rp)</label>
                            <input type="text" id="omset_per_bulan" name="omset_per_bulan" class="input-currency" inputmode="numeric" value="<?= esc($omsetFormatted) ?>" required>
                        </div>

                        <div class="sku-field">
                            <label for="deskripsi_usaha">Deskripsi Singkat Usaha (Opsional)</label>
                            <textarea id="deskripsi_usaha" name="deskripsi_usaha" rows="3" placeholder="Jelaskan aktivitas atau perkembangan usaha saat ini"><?= esc(old('deskripsi_usaha', (string) ($permohonan['deskripsi_usaha'] ?? ''))) ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <div class="sku-section-title">BERKAS PERSYARATAN</div>
                    <p class="revisi-subnote">Klik Lihat untuk memeriksa file saat ini. Unggah file baru hanya pada berkas yang ingin diganti.</p>

                    <div class="upload-stack">
                        <?php foreach ($dokumenSpecs as $jenis => $spec): ?>
                            <?php
                            $currentDok = $dokumenByJenis[$jenis] ?? null;
                            $acceptAttr = '.' . str_replace(',', ',.', $spec['exts']);
                            $extDisplay = strtoupper(str_replace(',', '/', $spec['exts']));
                            ?>
                            <div class="revisi-doc-block">
                                <div class="revisi-doc-current">
                                    <div>
                                        <div class="revisi-doc-title"><?= esc($spec['label']) ?></div>
                                        <div class="revisi-doc-filename">
                                            <?= $currentDok ? esc($currentDok['nama_file']) : 'Belum ada file terunggah' ?>
                                        </div>
                                    </div>
                                    <?php if ($currentDok): ?>
                                        <?php $isImg = strpos(strtolower((string) $currentDok['tipe_file']), 'image/') === 0; ?>
                                        <button
                                            type="button"
                                            class="btn-doc-view"
                                            data-doc-preview
                                            data-url="<?= base_url('pemohon/sku/dokumen/' . $currentDok['id']) ?>"
                                            data-title="<?= esc($spec['label'] . ' — ' . $currentDok['nama_file']) ?>"
                                            data-kind="<?= $isImg ? 'image' : 'pdf' ?>">
                                            Lihat
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div class="dropzone-card" data-accept="<?= esc($acceptAttr) ?>">
                                    <input
                                        type="file"
                                        name="<?= esc($spec['input']) ?>"
                                        accept="<?= esc($acceptAttr) ?>"
                                        class="dropzone-input">
                                    <div data-empty>
                                        <div class="dropzone-head">
                                            <span class="dropzone-prompt">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                Unggah file pengganti (opsional)
                                            </span>
                                            <span class="dropzone-hint"><?= esc($extDisplay) ?> &bull; Maks 2 MB</span>
                                        </div>
                                    </div>
                                    <div class="dropzone-preview" data-preview hidden>
                                        <div class="preview-thumb" data-thumb></div>
                                        <div class="preview-meta">
                                            <span class="preview-name" data-name></span>
                                            <span class="preview-size" data-size></span>
                                            <span class="dropzone-badge" data-badge></span>
                                        </div>
                                        <button type="button" class="preview-clear" data-clear aria-label="Batalkan file">&times;</button>
                                    </div>
                                    <div class="dropzone-error" data-error hidden></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="sku-form-footer">
                <a href="<?= base_url('pemohon/dashboard') ?>" class="btn-form-cancel">Batal</a>
                <button type="submit" class="btn-form-submit">Kirim Perbaikan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->include('pemohon/_sku_form_scripts') ?>
<?= view('pemohon/_doc_preview_modal', ['part' => 'body']) ?>
<?= $this->endSection() ?>
