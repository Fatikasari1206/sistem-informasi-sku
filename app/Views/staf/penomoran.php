<?= $this->extend('layouts/dashboard') ?>

<?php
$statusMap = [
    'menunggu_persetujuan_lurah' => ['label' => 'Siap TTE Lurah', 'class' => 'status-indigo'],
    'disetujui'                  => ['label' => 'Disahkan Lurah', 'class' => 'status-green'],
    'selesai'                    => ['label' => 'Terbit', 'class' => 'status-green'],
];
?>

<?= $this->section('styles') ?>
    <?= $this->include('staf/_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('staf/_sidebar_nav', ['activeMenu' => 'penomoran']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="panel-card">
        <div class="panel-head">
            <div>
                <h2 class="panel-title">Penerbitan Nomor Surat Keterangan Usaha (SKU)</h2>
                <p class="panel-subtitle">Berikan nomor surat resmi untuk berkas yang telah lolos verifikasi staf.</p>
            </div>
        </div>

        <?php if (empty($daftarSku)): ?>
            <div class="empty-state">
                <strong>Belum ada permohonan yang lolos ke tahap penomoran</strong>
                <span>Verifikasi berkas di menu Antrian Masuk terlebih dahulu sebelum menerbitkan nomor surat.</span>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="staf-table" id="staf-queue-table">
                    <thead>
                        <tr>
                            <th>NO. REGISTRASI</th>
                            <th>PEMOHON &amp; NIK</th>
                            <th>NAMA USAHA</th>
                            <th>STATUS</th>
                            <th>NOMOR SURAT SKU</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daftarSku as $row): ?>
                            <?php
                                $statusMeta = $statusMap[$row['status'] ?? 'menunggu_persetujuan_lurah'] ?? ['label' => 'Terproses', 'class' => 'status-neutral'];
                                $nilaiNomor = !empty($row['nomor_surat']) ? $row['nomor_surat'] : $saranNomor;
                            ?>
                            <tr data-padukuhan="<?= esc($row['padukuhan'] ?? '') ?>">
                                <td class="cell-strong"><?= esc($row['kode_pengajuan']) ?></td>
                                <td>
                                    <span class="pemohon-name"><?= esc($row['nama_pemohon']) ?></span>
                                    <span class="pemohon-nik"><?= esc($row['nik_pemohon']) ?></span>
                                </td>
                                <td class="cell-strong"><?= esc($row['nama_usaha']) ?></td>
                                <td>
                                    <span class="pill-badge <?= esc($statusMeta['class']) ?>">
                                        <?= esc($statusMeta['label']) ?>
                                    </span>
                                </td>
                                <td colspan="2">
                                    <form action="<?= base_url('staf/penomoran/' . (int) $row['id']) ?>" method="POST" class="nomor-form">
                                        <?= csrf_field() ?>
                                        <input
                                            type="text"
                                            name="nomor_surat"
                                            class="inline-input"
                                            value="<?= esc($nilaiNomor) ?>"
                                            required
                                        >
                                        <button type="submit" class="action-btn <?= !empty($row['nomor_surat']) ? 'slate' : 'emerald' ?>">
                                            <?= !empty($row['nomor_surat']) ? 'Perbarui' : 'Simpan Nomor' ?>
                                        </button>
                                    </form>
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
