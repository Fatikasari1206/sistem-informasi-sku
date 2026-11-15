<?= $this->extend('layouts/dashboard') ?>

<?php
$statusMap = [
    'diajukan'        => ['label' => 'Menunggu Staf', 'class' => 'status-amber'],
    'verifikasi_staf' => ['label' => 'Sedang Diperiksa', 'class' => 'status-indigo'],
    'revisi'          => ['label' => 'Menunggu Revisi Warga', 'class' => 'status-red'],
];
?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('staf/_sidebar_nav', ['activeMenu' => 'verifikasi']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Daftar Verifikasi Berkas &amp; Revisi</h2>
                <p class="panel-subtitle">Periksa kelengkapan berkas persyaratan atau pantau permohonan yang diminta revisi.</p>
            </div>

            <select id="filter-padukuhan" class="filter-select" aria-label="Filter berdasarkan padukuhan">
                <option value="">Padukuhan</option>
                <?php foreach ($padukuhanList as $p): ?>
                    <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (empty($antrean)): ?>
            <div class="empty-state">
                <strong>Tidak ada berkas dalam antrian verifikasi</strong>
                <span>Permohonan yang perlu diverifikasi atau direvisi akan tampil di sini.</span>
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
                            <th>BERKAS</th>
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
                                        <?= (int) $docCount ?> Berkas
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
        <?php endif; ?>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->include('staf/_table_filter_script') ?>
<?= $this->endSection() ?>
