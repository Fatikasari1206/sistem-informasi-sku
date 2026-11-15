<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('lurah/_sidebar_nav', ['activeMenu' => 'antrian']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="panel-card">
        <div class="panel-head">
            <h2 class="panel-title">Antrian Pengesahan Surat Keterangan Usaha (SKU)</h2>

            <select id="filter-padukuhan" class="filter-select" aria-label="Filter berdasarkan padukuhan">
                <option value="">Padukuhan</option>
                <?php foreach ($padukuhanList as $p): ?>
                    <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (empty($antrean)): ?>
            <div class="empty-state">
                <strong>Belum ada surat yang menunggu pengesahan TTE</strong>
                <span>Semua permohonan SKU yang lolos verifikasi staf telah disahkan.</span>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="staf-table" id="staf-queue-table">
                    <thead>
                        <tr>
                            <th>NO. REGISTRASI</th>
                            <th>PEMOHON &amp; NIK</th>
                            <th>NAMA USAHA</th>
                            <th>PADUKUHAN</th>
                            <th>NOMOR SURAT SKU</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($antrean as $row): ?>
                            <?php $padukuhan = !empty($row['padukuhan']) ? $row['padukuhan'] : '-'; ?>
                            <tr data-padukuhan="<?= esc($padukuhan) ?>">
                                <td class="cell-strong"><?= esc($row['kode_pengajuan']) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_pemohon']) ?></span>
                                    <span class="pemohon-nik"><?= esc($row['nik_pemohon']) ?></span>
                                </td>
                                <td class="cell-strong"><?= esc($row['nama_usaha']) ?></td>
                                <td><?= esc($padukuhan) ?></td>
                                <td class="cell-strong"><?= esc($row['nomor_surat'] ?: '-') ?></td>
                                <td>
                                    <span class="pill-badge status-indigo">Siap TTE Digital</span>
                                </td>
                                <td>
                                    <a href="<?= base_url('lurah/tte/' . (int) $row['id']) ?>" class="action-btn">Sahkan TTE</a>
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
