<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('lurah/_sidebar_nav', ['activeMenu' => 'arsip']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Arsip Surat Keterangan Usaha (SKU) Terbit</h2>
                <p class="panel-subtitle">Daftar SKU yang telah disahkan secara elektronik oleh Lurah Condongcatur.</p>
            </div>

            <select id="filter-padukuhan" class="filter-select" aria-label="Filter berdasarkan padukuhan">
                <option value="">Padukuhan</option>
                <?php foreach ($padukuhanList as $p): ?>
                    <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (empty($daftarSku)): ?>
            <div class="empty-state">
                <strong>Belum ada SKU yang diterbitkan</strong>
                <span>SKU yang sudah disahkan melalui portal TTE akan tercatat rapi di arsip ini.</span>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="staf-table" id="staf-queue-table">
                    <thead>
                        <tr>
                            <th>NO. SURAT SKU</th>
                            <th>PEMOHON &amp; NIK</th>
                            <th>NAMA USAHA</th>
                            <th>PADUKUHAN</th>
                            <th>TANGGAL SAH</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarSku as $row): ?>
                            <?php
                                $padukuhan = !empty($row['padukuhan']) ? $row['padukuhan'] : '-';
                                $tglSah    = !empty($row['disetujui_pada']) ? date('d M Y', strtotime($row['disetujui_pada'])) : date('d M Y', strtotime($row['updated_at']));
                            ?>
                            <tr data-padukuhan="<?= esc($padukuhan) ?>">
                                <td class="cell-strong"><?= esc($row['nomor_surat'] ?: $row['kode_pengajuan']) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_pemohon']) ?></span>
                                    <span class="pemohon-nik"><?= esc($row['nik_pemohon']) ?></span>
                                </td>
                                <td class="cell-strong"><?= esc($row['nama_usaha']) ?></td>
                                <td><?= esc($padukuhan) ?></td>
                                <td><?= esc($tglSah) ?></td>
                                <td>
                                    <span class="pill-badge status-green">Disahkan TTE</span>
                                </td>
                                <td>
                                    <a href="<?= base_url('lurah/tte/' . (int) $row['id']) ?>" class="action-btn slate">Lihat Surat</a>
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
