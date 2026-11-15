<?= $this->extend('layouts/dashboard') ?>

<?php
$statusMap = [
    'diajukan'        => ['label' => 'Menunggu Staf', 'class' => 'status-amber'],
    'verifikasi_staf' => ['label' => 'Sedang Diperiksa', 'class' => 'status-indigo'],
];
?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('staf/_sidebar_nav', ['activeMenu' => 'antrian']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="panel-card">
        <div class="panel-head">
            <h2 class="panel-title">Antrian Permohonan Surat Keterangan Usaha (SKU) Masuk</h2>

            <select id="filter-padukuhan" class="filter-select" aria-label="Filter berdasarkan padukuhan">
                <option value="">Padukuhan</option>
                <?php foreach ($padukuhanList as $p): ?>
                    <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (empty($antrean)): ?>
            <div class="empty-state">
                <strong>Belum ada antrian permohonan SKU masuk</strong>
                <span>Semua pengajuan dari warga sudah selesai diperiksa.</span>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="staf-table" id="staf-queue-table">
                    <thead>
                        <tr>
                            <th>NO. REGISTRASI</th>
                            <th>PEMOHON &amp; NIK</th>
                            <th>NAMA USAHA</th>
                            <th>LAYANAN</th>
                            <th>PADUKUHAN</th>
                            <th>SYARAT</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($antrean as $row): ?>
                            <?php
                                $isPerp     = ($row['tipe_pengajuan'] ?? 'baru') === 'perpanjangan';
                                $padukuhan  = !empty($row['padukuhan']) ? $row['padukuhan'] : '-';
                                $docCount   = (int) ($row['total_dokumen'] ?? 0);
                                $reqDocs    = $isPerp ? 3 : 4;
                                $isComplete = $docCount >= $reqDocs;
                                $syaratText = $isComplete ? "✓ {$docCount}/{$reqDocs} Lengkap" : "{$docCount}/{$reqDocs} Berkas";
                                $statusMeta = $statusMap[$row['status'] ?? 'diajukan'] ?? ['label' => 'Menunggu Staf', 'class' => 'status-amber'];
                            ?>
                            <tr data-padukuhan="<?= esc($padukuhan) ?>">
                                <td class="cell-strong"><?= esc($row['kode_pengajuan']) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_pemohon']) ?></span>
                                    <span class="pemohon-nik"><?= esc($row['nik_pemohon']) ?></span>
                                </td>
                                <td class="cell-strong"><?= esc($row['nama_usaha']) ?></td>
                                <td>
                                    <span class="pill-badge <?= $isPerp ? 'service-perpanjangan' : 'service-baru' ?>">
                                        <?= $isPerp ? 'Perpanjangan' : 'SKU Baru' ?>
                                    </span>
                                </td>
                                <td><?= esc($padukuhan) ?></td>
                                <td>
                                    <span class="pill-badge <?= $isComplete ? 'status-green' : 'status-amber' ?>">
                                        <?= esc($syaratText) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="pill-badge <?= esc($statusMeta['class']) ?>">
                                        <?= esc($statusMeta['label']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('staf/periksa/' . (int) $row['id']) ?>" class="action-btn">Periksa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-pagination-footer" id="staf-pagination"></div>
        <?php endif; ?>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->include('staf/_table_filter_script') ?>
<?= $this->endSection() ?>
