<?= $this->extend('layouts/dashboard') ?>

<?php
$statusMap = [
    'diajukan'                   => ['label' => 'Menunggu Staf', 'class' => 'status-amber'],
    'verifikasi_staf'            => ['label' => 'Sedang Diperiksa', 'class' => 'status-indigo'],
    'revisi'                     => ['label' => 'Perlu Revisi', 'class' => 'status-red'],
    'menunggu_persetujuan_lurah' => ['label' => 'Menunggu Lurah', 'class' => 'status-indigo'],
    'disetujui'                  => ['label' => 'Disahkan Lurah', 'class' => 'status-green'],
    'selesai'                    => ['label' => 'Selesai', 'class' => 'status-green'],
    'ditolak'                    => ['label' => 'Ditolak', 'class' => 'status-red'],
];
?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('staf/_sidebar_nav', ['activeMenu' => 'dashboard']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- 3 KPI Cards -->
    <section class="kpi-grid-3" aria-label="Ringkasan Pengajuan SKU">
        <div class="kpi-card">
            <div class="kpi-label">TOTAL PENGAJUAN SKU</div>
            <div class="kpi-value"><?= (int) $stats['total_sku'] ?> Pengajuan</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">PERLU VERIFIKASI STAF</div>
            <div class="kpi-value"><?= (int) $stats['perlu_verifikasi'] ?> Berkas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">SIAP TTE LURAH</div>
            <div class="kpi-value"><?= (int) $stats['siap_tte'] ?> Berkas</div>
        </div>
    </section>

    <!-- Tabel Informasi Pengajuan SKU -->
    <section class="panel-card">
        <div class="panel-head">
            <h2 class="panel-title">Daftar Pengajuan Surat Keterangan Usaha (SKU)</h2>

            <select id="filter-padukuhan" class="filter-select" aria-label="Filter berdasarkan padukuhan">
                <option value="">Padukuhan</option>
                <?php foreach ($padukuhanList as $p): ?>
                    <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (empty($daftarSku)): ?>
            <div class="empty-state">
                <strong>Belum ada pengajuan SKU masuk</strong>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="staf-table" id="staf-queue-table">
                    <thead>
                        <tr>
                            <th>NO. REGISTRASI / SKU</th>
                            <th>PEMOHON &amp; NIK</th>
                            <th>NAMA USAHA</th>
                            <th>PADUKUHAN</th>
                            <th>TANGGAL</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarSku as $row): ?>
                            <?php
                                $statusKey   = $row['status'] ?? 'diajukan';
                                $statusMeta  = $statusMap[$statusKey] ?? ['label' => 'Diproses', 'class' => 'status-neutral'];
                                $padukuhan   = !empty($row['padukuhan']) ? $row['padukuhan'] : '-';
                                $nomorTampil = !empty($row['nomor_surat']) ? $row['nomor_surat'] : $row['kode_pengajuan'];
                                $needCheck   = in_array($statusKey, ['diajukan', 'verifikasi_staf'], true);
                            ?>
                            <tr data-padukuhan="<?= esc($padukuhan) ?>">
                                <td class="cell-strong"><?= esc($nomorTampil) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_pemohon']) ?></span>
                                    <span class="pemohon-nik"><?= esc($row['nik_pemohon']) ?></span>
                                </td>
                                <td class="cell-strong"><?= esc($row['nama_usaha']) ?></td>
                                <td><?= esc($padukuhan) ?></td>
                                <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <span class="pill-badge <?= esc($statusMeta['class']) ?>">
                                        <?= esc($statusMeta['label']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('staf/periksa/' . (int) $row['id']) ?>" class="action-btn <?= $needCheck ? '' : 'slate' ?>">
                                        <?= $needCheck ? 'Periksa' : 'Lihat' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="staf-pagination"></div>
        <?php endif; ?>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->include('staf/_table_filter_script') ?>
<?= $this->endSection() ?>
