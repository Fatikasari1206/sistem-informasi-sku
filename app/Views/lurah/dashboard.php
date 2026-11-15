<?= $this->extend('layouts/dashboard') ?>

<?php
$statusMap = [
    'diajukan'                   => ['label' => 'Menunggu Staf', 'class' => 'status-amber'],
    'verifikasi_staf'            => ['label' => 'Verifikasi Staf', 'class' => 'status-neutral'],
    'revisi'                     => ['label' => 'Perlu Revisi', 'class' => 'status-red'],
    'menunggu_persetujuan_lurah' => ['label' => 'Siap TTE', 'class' => 'status-indigo'],
    'disetujui'                  => ['label' => 'Sah Legal', 'class' => 'status-green'],
    'selesai'                    => ['label' => 'Sah Legal', 'class' => 'status-green'],
    'ditolak'                    => ['label' => 'Ditolak', 'class' => 'status-red'],
];

$barColors = ['#6d28d9', '#4f46e5', '#3b82f6', '#06b6d4', '#10b981', '#f59e0b'];
$maxTotal  = 1;
foreach ($padukuhanDistribusi as $item) {
    if ((int) $item['total'] > $maxTotal) {
        $maxTotal = (int) $item['total'];
    }
}
?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('lurah/_sidebar_nav', ['activeMenu' => 'dashboard']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- 3 KPI Cards -->
    <section class="kpi-grid-3" aria-label="Ringkasan Pengajuan SKU">
        <div class="kpi-card">
            <div class="kpi-label">TOTAL PENGAJUAN SKU</div>
            <div class="kpi-value"><?= (int) $stats['total_sku'] ?> Pengajuan</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">BERKAS MENUNGGU TTE</div>
            <div class="kpi-value"><?= (int) $stats['menunggu_tte'] ?> Berkas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">PADUKUHAN TERAKTIF</div>
            <div class="kpi-value"><?= esc($stats['top_padukuhan']) ?></div>
        </div>
    </section>

    <!-- Grafik Distribusi Wilayah Padukuhan (Top 6) -->
    <section class="panel-card">
        <div class="panel-head">
            <h2 class="panel-title">Distribusi Pengajuan SKU Berdasarkan Wilayah Padukuhan (Top 6)</h2>
        </div>

        <?php if (empty($padukuhanDistribusi)): ?>
            <div class="empty-state">
                <strong>Belum ada data distribusi pengajuan SKU</strong>
            </div>
        <?php else: ?>
            <div class="chart-list">
                <?php foreach ($padukuhanDistribusi as $idx => $item): ?>
                    <?php
                        $count = (int) $item['total'];
                        $pct   = max(8, (int) round(($count / $maxTotal) * 100));
                        $color = $barColors[$idx % count($barColors)];
                    ?>
                    <div class="chart-row">
                        <span class="chart-label">Padukuhan <?= esc($item['padukuhan']) ?></span>
                        <div class="chart-track">
                            <div class="chart-fill" style="width: <?= $pct ?>%; background: <?= $color ?>;"></div>
                        </div>
                        <span class="chart-count"><?= $count ?> SKU</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Tabel Informasi Pengajuan & Pengesahan SKU -->
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
                <strong>Belum ada pengajuan SKU</strong>
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
                                $statusKey  = $row['status'] ?? 'diajukan';
                                $statusMeta = $statusMap[$statusKey] ?? ['label' => 'Diproses', 'class' => 'status-neutral'];
                                $padukuhan  = !empty($row['padukuhan']) ? $row['padukuhan'] : '-';
                                $nomorTampil = !empty($row['nomor_surat']) ? $row['nomor_surat'] : $row['kode_pengajuan'];
                                $waktuTampil = !empty($row['disetujui_pada']) ? $row['disetujui_pada'] : $row['created_at'];
                                $canSign    = $statusKey === 'menunggu_persetujuan_lurah';
                            ?>
                            <tr data-padukuhan="<?= esc($padukuhan) ?>">
                                <td class="cell-strong"><?= esc($nomorTampil) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_pemohon']) ?></span>
                                    <span class="pemohon-nik"><?= esc($row['nik_pemohon']) ?></span>
                                </td>
                                <td class="cell-strong"><?= esc($row['nama_usaha']) ?></td>
                                <td><?= esc($padukuhan) ?></td>
                                <td><?= date('d M Y H:i', strtotime($waktuTampil)) ?></td>
                                <td>
                                    <span class="pill-badge <?= esc($statusMeta['class']) ?>">
                                        <?= esc($statusMeta['label']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('lurah/tte/' . (int) $row['id']) ?>" class="action-btn <?= $canSign ? '' : 'slate' ?>">
                                        <?= $canSign ? 'Sahkan TTE' : 'Lihat' ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="staf-pagination"></div>

            <?php if ((int) $stats['menunggu_tte'] > 0): ?>
                <div class="panel-footer-cta">
                    <a href="<?= base_url('lurah/pengesahan') ?>" class="cta-btn-lg">Buka Ruang Penandatanganan TTE</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->include('staf/_table_filter_script') ?>
<?= $this->endSection() ?>
