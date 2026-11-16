<?= $this->extend('layouts/dashboard') ?>

<?php
$dokumenList = [
    [
        'input'    => 'dokumen_pengantar_rt_rw',
        'title'    => '1. Surat Pengantar RT/RW/Dukuh',
        'accept'   => '.pdf,.jpg,.jpeg,.png',
        'hint'     => 'Foto atau PDF, maks 2 MB',
        'required' => true,
    ],
    [
        'input'    => 'dokumen_ktp',
        'title'    => '2. KTP Pemohon',
        'accept'   => '.pdf,.jpg,.jpeg,.png',
        'hint'     => 'Foto atau PDF, maks 2 MB',
        'required' => true,
    ],
    [
        'input'    => 'dokumen_kk',
        'title'    => '3. Kartu Keluarga (KK)',
        'accept'   => '.pdf,.jpg,.jpeg,.png',
        'hint'     => 'Foto atau PDF, maks 2 MB',
        'required' => true,
    ],
    [
        'input'    => 'dokumen_foto_usaha',
        'title'    => '4. Foto Tempat Usaha',
        'accept'   => '.jpg,.jpeg,.png',
        'hint'     => 'Foto tempat usaha, maks 2 MB',
        'required' => true,
    ],
];
?>

<?= $this->section('styles') ?>
    <?= $this->include('pemohon/_sku_form_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('pemohon/_sidebar_nav', ['activeMenu' => 'baru']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="sku-form-card">
        <div class="sku-form-header">
            <div>
                <h1>Pengajuan SKU Baru</h1>
                <p>Kalurahan Condongcatur, Kapanewon Depok</p>
            </div>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="sku-form-alert">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('pemohon/sku/baru') ?>" method="POST" enctype="multipart/form-data" id="form-sku-baru" data-submit-guard>
            <?= csrf_field() ?>

            <div class="sku-form-grid">
                <div>
                    <div class="sku-section-title">Informasi Usaha</div>

                    <div class="sku-field">
                        <label for="nama_usaha">Nama Usaha</label>
                        <input type="text" name="nama_usaha" id="nama_usaha" value="<?= esc(old('nama_usaha')) ?>" placeholder="Misal: Warung Makan Berkah" required autofocus>
                    </div>

                    <div class="sku-field">
                        <label for="bidang_usaha">Jenis Usaha</label>
                        <input type="text" name="bidang_usaha" id="bidang_usaha" value="<?= esc(old('bidang_usaha')) ?>" placeholder="Misal: Kuliner, Sembako, atau Jasa" required>
                    </div>

                    <div class="sku-field">
                        <label for="alamat_usaha">Alamat Lengkap Usaha</label>
                        <input type="text" name="alamat_usaha" id="alamat_usaha" value="<?= esc(old('alamat_usaha')) ?>" placeholder="Misal: Jl. Affandi No. 24, RT 04 / RW 08" required>
                    </div>

                    <div class="sku-field">
                        <label for="padukuhan_usaha">Padukuhan</label>
                        <select name="padukuhan_usaha" id="padukuhan_usaha" required>
                            <option value="">Pilih padukuhan</option>
                            <?php foreach ($padukuhanList as $padukuhan): ?>
                                <option value="<?= esc($padukuhan) ?>" <?= old('padukuhan_usaha') === $padukuhan ? 'selected' : '' ?>>
                                    <?= esc($padukuhan) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="sku-subgrid-2">
                        <div class="sku-field">
                            <label for="lama_usaha_tahun">Lama Usaha (Tahun)</label>
                            <input type="number" name="lama_usaha_tahun" id="lama_usaha_tahun" min="0" max="99" value="<?= esc(old('lama_usaha_tahun')) ?>" placeholder="Misal: 3" required>
                        </div>

                        <div class="sku-field">
                            <label for="omset_per_bulan">Omset per Bulan (Rp)</label>
                            <input type="text" name="omset_per_bulan" id="omset_per_bulan" class="input-currency" inputmode="numeric" value="<?= esc(old('omset_per_bulan')) ?>" placeholder="Misal: 5.000.000" required>
                        </div>
                    </div>

                    <div class="sku-field" style="margin-bottom: 0;">
                        <label for="status_tempat_usaha">Status Tempat Usaha</label>
                        <select name="status_tempat_usaha" id="status_tempat_usaha" required>
                            <option value="">Pilih status tempat</option>
                            <option value="milik_sendiri" <?= old('status_tempat_usaha') === 'milik_sendiri' ? 'selected' : '' ?>>Milik Sendiri</option>
                            <option value="sewa" <?= old('status_tempat_usaha') === 'sewa' ? 'selected' : '' ?>>Sewa / Kontrak</option>
                            <option value="menumpang" <?= old('status_tempat_usaha') === 'menumpang' ? 'selected' : '' ?>>Menumpang</option>
                            <option value="lainnya" <?= old('status_tempat_usaha') === 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="sku-section-title">Berkas Persyaratan</div>
                    <?= view('pemohon/_dropzone_list', ['dokumenList' => $dokumenList]) ?>
                </div>
            </div>

            <div class="sku-form-footer">
                <a href="<?= base_url('pemohon/dashboard') ?>" class="btn-form-cancel">Batal</a>
                <button type="submit" class="btn-form-submit">Kirim Permohonan</button>
            </div>
        </form>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->include('pemohon/_sku_form_scripts') ?>
<?= $this->endSection() ?>
