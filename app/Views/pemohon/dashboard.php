<?= $this->extend('layouts/dashboard') ?>

<?php
$formatTanggal = static function (?string $date): string {
    if (empty($date)) {
        return '-';
    }
    $months = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
    ];
    $ts = strtotime($date);
    if ($ts === false) {
        return '-';
    }
    return date('d', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
};

$statusMeta = [
    'draft'                      => ['label' => 'Draft', 'class' => 'status-neutral', 'icon' => ''],
    'diajukan'                   => ['label' => 'Menunggu Verifikasi', 'class' => 'status-amber', 'icon' => ''],
    'verifikasi_staf'            => ['label' => 'Verifikasi Staf', 'class' => 'status-amber', 'icon' => ''],
    'revisi'                     => ['label' => 'Perlu Revisi', 'class' => 'status-red', 'icon' => '! '],
    'menunggu_persetujuan_lurah' => ['label' => 'Menunggu TTE Lurah', 'class' => 'status-indigo', 'icon' => ''],
    'disetujui'                  => ['label' => 'Disahkan Lurah', 'class' => 'status-green', 'icon' => '✓ '],
    'selesai'                    => ['label' => 'Disahkan Lurah', 'class' => 'status-green', 'icon' => '✓ '],
    'ditolak'                    => ['label' => 'Ditolak', 'class' => 'status-neutral', 'icon' => '✕ '],
];
?>

<?= $this->section('styles') ?>
<style>
    /* Hero Banner */
    .hero-banner {
        background: #272c50;
        border-radius: 14px;
        padding: 1.3rem 1.65rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
        flex-wrap: wrap;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }

    .hero-banner-copy h1 {
        font-size: 1.18rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.015em;
        margin-bottom: 0.25rem;
    }

    .hero-banner-copy p {
        font-size: 0.78rem;
        color: #9ba3c2;
    }

    /* 4 Stat Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.95rem;
    }

    .kpi-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 0.95rem 1.15rem;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .kpi-label {
        font-size: 0.66rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
    }

    .kpi-value {
        font-size: 1.08rem;
        font-weight: 800;
        color: #d97706;
        letter-spacing: -0.01em;
    }

    .kpi-value.danger {
        color: #dc2626;
    }

    /* Table Card */
    .history-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.4rem 1.4rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .history-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.9rem;
    }

    .table-wrap {
        overflow-x: auto;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.4rem;
    }

    .sku-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        text-align: left;
    }

    .sku-table thead tr {
        background: #272c50;
    }

    .sku-table thead th {
        padding: 0.72rem 0.95rem;
        font-size: 0.68rem;
        font-weight: 600;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .sku-table thead th:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    .sku-table thead th:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
        text-align: center;
    }

    .sku-table tbody td {
        padding: 0.9rem 0.95rem;
        font-size: 0.78rem;
        color: #334155;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        white-space: nowrap;
        transition: background-color 0.12s ease;
    }

    .sku-table tbody tr:nth-child(even) td {
        background: #f1f5f9;
    }

    .sku-table tbody tr:last-child td {
        border-bottom: none;
    }

    .sku-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }

    .sku-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    .sku-table tbody tr:hover td {
        background: #e2e8f0;
    }

    .sku-table tbody td:last-child {
        text-align: center;
    }

    .cell-strong {
        font-weight: 700;
        color: #0f172a;
    }

    .pill-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.28rem 0.72rem;
        border-radius: 9999px;
        font-size: 0.71rem;
        font-weight: 600;
    }

    .service-pill {
        background: #e0e7ff;
        color: #4338ca;
    }

    .status-amber {
        background: #fef3c7;
        color: #b45309;
    }

    .status-green {
        background: #dcfce7;
        color: #15803d;
    }

    .status-indigo {
        background: #ede9fe;
        color: #6d28d9;
    }

    .status-red {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-neutral {
        background: #f1f5f9;
        color: #475569;
    }

    .action-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 56px;
        padding: 0.34rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 600;
        color: #ffffff;
        text-decoration: none;
        transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .action-pill:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }

    .action-info { background: #5246f0; }
    .action-pdf  { background: #0d9468; }
    .action-fix  { background: #dc2626; }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #64748b;
        font-size: 0.84rem;
    }

    .empty-state strong {
        display: block;
        color: #0f172a;
        font-size: 0.95rem;
        margin-bottom: 0.35rem;
    }

    /* Table Pagination Footer */
    .table-pagination-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 0.85rem;
        padding: 0.35rem 0.25rem 0.15rem;
    }

    .pagination-summary {
        font-size: 0.76rem;
        color: #64748b;
        font-weight: 500;
    }

    .pagination-summary strong {
        color: #0f172a;
        font-weight: 700;
    }

    .pagination-pill-box {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        padding: 0.28rem 0.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }

    .pag-btn {
        min-width: 28px;
        height: 28px;
        padding: 0 0.4rem;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #475569;
        font-size: 0.76rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.12s ease;
        user-select: none;
    }

    .pag-btn:hover:not(:disabled):not(.is-active) {
        background: #f1f5f9;
        color: #0f172a;
    }

    .pag-btn.is-active {
        background: #5246f0;
        color: #ffffff;
        font-weight: 700;
    }

    .pag-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .pag-ellipsis {
        padding: 0 0.2rem;
        font-size: 0.76rem;
        color: #94a3b8;
        user-select: none;
    }

    @media (max-width: 1024px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 560px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('pemohon/_sidebar_nav', ['activeMenu' => 'dashboard']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Banner Layanan SKU -->
    <section class="hero-banner">
        <div class="hero-banner-copy">
            <h1>Layanan Surat Keterangan Usaha (SKU)</h1>
            <p>Kalurahan Condongcatur melayani pengajuan SKU Baru dan Perpanjangan masa berlaku 1 tahun.</p>
        </div>
    </section>

    <!-- 4 KPI Cards -->
    <section class="kpi-grid" aria-label="Ringkasan Status SKU">
        <div class="kpi-card">
            <div class="kpi-label">TOTAL SKU AKTIF</div>
            <div class="kpi-value"><?= (int) $stats['total_sku_aktif'] ?> Surat Legal</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">DALAM PROSES VERIFIKASI</div>
            <div class="kpi-value"><?= (int) $stats['dalam_verifikasi'] ?> Berkas Masuk</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">MENUNGGU TTE LURAH</div>
            <div class="kpi-value"><?= (int) $stats['menunggu_lurah'] ?> Berkas</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">MASA BERLAKU TERDEKAT</div>
            <div class="kpi-value danger"><?= esc($formatTanggal($stats['masa_berlaku_terdekat'] ?? null)) ?></div>
        </div>
    </section>

    <!-- Riwayat & Monitoring Table -->
    <section class="history-card">
        <h2 class="history-title">Riwayat &amp; Monitoring Status Permohonan SKU</h2>

        <?php if (empty($permohonanList)): ?>
            <div class="empty-state">
                <strong>Belum ada riwayat permohonan SKU</strong>
                <span>Pilih menu <b>Permohonan SKU Baru</b> di sidebar kiri untuk mulai mengajukan surat keterangan usaha Anda.</span>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="sku-table" id="sku-history-table">
                    <thead>
                        <tr>
                            <th>NO. REGISTRASI</th>
                            <th>NAMA USAHA</th>
                            <th>LAYANAN</th>
                            <th>PADUKUHAN</th>
                            <th>TANGGAL</th>
                            <th>STATUS BERKAS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($permohonanList as $row): ?>
                            <?php
                                $statusKey = $row['status'] ?? 'diajukan';
                                $meta      = $statusMeta[$statusKey] ?? ['label' => ucfirst(str_replace('_', ' ', $statusKey)), 'class' => 'status-neutral', 'icon' => ''];
                                $layanan   = ($row['tipe_pengajuan'] ?? 'baru') === 'perpanjangan' ? 'Perpanjangan' : 'SKU Baru';
                                $padukuhan = !empty($row['padukuhan']) ? 'Padukuhan ' . $row['padukuhan'] : ($row['alamat_usaha'] ?? '-');
                            ?>
                            <tr class="sku-row">
                                <td class="cell-strong"><?= esc($row['kode_pengajuan']) ?></td>
                                <td class="cell-strong"><?= esc($row['nama_usaha']) ?></td>
                                <td><span class="pill-badge service-pill"><?= esc($layanan) ?></span></td>
                                <td><?= esc($padukuhan) ?></td>
                                <td><?= esc($formatTanggal($row['created_at'] ?? null)) ?></td>
                                <td>
                                    <span class="pill-badge <?= esc($meta['class']) ?>">
                                        <?= esc($meta['icon'] . $meta['label']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (in_array($statusKey, ['disetujui', 'selesai'], true)): ?>
                                        <a href="<?= base_url('pemohon/sku/cetak/' . $row['id']) ?>" target="_blank" class="action-pill action-pdf">PDF</a>
                                    <?php elseif ($statusKey === 'revisi'): ?>
                                        <a href="<?= base_url('pemohon/sku/perbaiki/' . $row['id']) ?>" class="action-pill action-fix">Revisi</a>
                                    <?php else: ?>
                                        <a href="<?= base_url('pemohon/sku/detail/' . $row['id']) ?>" class="action-pill action-info">Info</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="pemohon-pagination"></div>
        <?php endif; ?>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= view('partials/_pagination_script') ?>
    <script>
        (function () {
            window.initTablePagination({
                tableId: 'sku-history-table',
                containerId: 'pemohon-pagination',
                pageSize: 5,
                itemLabel: 'riwayat SKU'
            });
        })();
    </script>
<?= $this->endSection() ?>
