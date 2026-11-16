<?= $this->extend('layouts/dashboard') ?>

<?php
$dokumenPerpanjangan = [
    [
        'input'    => 'dokumen_perpanjangan_sku_lama',
        'title'    => '1. Lembar SKU Asli Lama',
        'accept'   => '.pdf,.jpg,.jpeg,.png',
        'hint'     => 'Foto atau PDF, maks 2 MB',
        'required' => true,
    ],
    [
        'input'    => 'dokumen_perpanjangan_pengantar',
        'title'    => '2. Surat Pengantar RT/RW/Dukuh Terbaru',
        'accept'   => '.pdf,.jpg,.jpeg,.png',
        'hint'     => 'Foto atau PDF, maks 2 MB',
        'required' => true,
    ],
    [
        'input'    => 'dokumen_perpanjangan_identitas',
        'title'    => '3. KTP & Kartu Keluarga',
        'accept'   => '.pdf,.jpg,.jpeg,.png',
        'hint'     => 'Foto atau PDF, maks 2 MB',
        'required' => true,
    ],
    [
        'input'    => 'dokumen_perpanjangan_foto_usaha',
        'title'    => '4. Foto Tempat Usaha Terbaru',
        'accept'   => '.jpg,.jpeg,.png',
        'hint'     => 'Tidak wajib • Foto, maks 2 MB',
        'required' => false,
    ],
];

$hasSkuLama = !empty($skuLamaList);
$todayDate  = date('Y-m-d');
?>

<?= $this->section('styles') ?>
    <?= $this->include('pemohon/_sku_form_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('sidebar_nav') ?>
    <?= view('pemohon/_sidebar_nav', ['activeMenu' => 'perpanjangan']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="sku-form-card">
        <div class="sku-form-header">
            <div>
                <h1>Perpanjangan SKU</h1>
                <p>Masa berlaku SKU 1 tahun sejak diterbitkan</p>
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

        <?php if (!$hasSkuLama): ?>
            <div class="sku-empty-box">
                <strong>Belum ada SKU terbit yang dapat diperpanjang</strong>
                <p>Perpanjangan hanya tersedia untuk SKU yang sudah disahkan Lurah.</p>
                <div class="sku-form-footer" style="border-top: none; justify-content: center; margin-top: 0.75rem;">
                    <a href="<?= base_url('pemohon/dashboard') ?>" class="btn-form-cancel">Kembali</a>
                    <a href="<?= base_url('pemohon/sku/baru') ?>" class="btn-form-submit">Ajukan SKU Baru</a>
                </div>
            </div>
        <?php else: ?>
            <form action="<?= base_url('pemohon/sku/perpanjangan') ?>" method="POST" enctype="multipart/form-data" id="form-sku-perpanjangan" data-submit-guard>
                <?= csrf_field() ?>

                <div class="sku-form-grid">
                    <div>
                        <div class="sku-section-title">Data SKU Sebelumnya</div>

                        <div class="sku-field">
                            <label for="permohonan_sebelumnya_id">Surat SKU yang Diperpanjang</label>
                            <select name="permohonan_sebelumnya_id" id="permohonan_sebelumnya_id" required>
                                <?php foreach ($skuLamaList as $index => $item): ?>
                                    <?php
                                        $nomorRef    = !empty($item['nomor_surat']) ? $item['nomor_surat'] : $item['kode_pengajuan'];
                                        $tglMulai    = format_tanggal_indo($item['tanggal_surat'] ?? $item['disetujui_pada'] ?? $item['created_at']);
                                        $tglAkhirRaw = $item['berlaku_sampai'] ?? null;
                                        $tglAkhir    = format_tanggal_indo($tglAkhirRaw);
                                        $isExpired   = !empty($tglAkhirRaw) && $tglAkhirRaw < $todayDate;
                                        $masaText    = !empty($tglAkhirRaw)
                                            ? "{$tglMulai} s.d. {$tglAkhir} (" . ($isExpired ? 'Kedaluwarsa' : 'Masih Aktif') . ')'
                                            : "Terbit {$tglMulai}";
                                        $selected    = old('permohonan_sebelumnya_id')
                                            ? ((string) old('permohonan_sebelumnya_id') === (string) $item['id'])
                                            : ($index === 0);
                                    ?>
                                    <option
                                        value="<?= (int) $item['id'] ?>"
                                        data-masa="<?= esc($masaText) ?>"
                                        data-expired="<?= $isExpired ? '1' : '0' ?>"
                                        data-omset="<?= esc((string) ($item['omset_per_bulan'] ?? '')) ?>"
                                        data-alamat="<?= esc((string) ($item['alamat_usaha'] ?? '')) ?>"
                                        data-deskripsi="<?= esc((string) ($item['deskripsi_usaha'] ?? '')) ?>"
                                        <?= $selected ? 'selected' : '' ?>
                                    >
                                        <?= esc($nomorRef . ' — ' . $item['nama_usaha']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="sku-field">
                            <label for="perpanjangan_masa_berlaku">Masa Berlaku Sebelumnya</label>
                            <input type="text" id="perpanjangan_masa_berlaku" readonly>
                        </div>

                        <label class="sku-inline-check" for="ada_pembaruan">
                            <input type="checkbox" name="ada_pembaruan" id="ada_pembaruan" value="1" <?= old('ada_pembaruan') ? 'checked' : '' ?>>
                            <span>Ubah data usaha (omset, alamat, atau keterangan)</span>
                        </label>

                        <div class="sku-field">
                            <label for="perpanjangan_omset">Omset per Bulan (Rp)</label>
                            <input type="text" name="omset_per_bulan" id="perpanjangan_omset" class="input-currency" inputmode="numeric" value="<?= esc(old('omset_per_bulan')) ?>" placeholder="Misal: 5.000.000">
                        </div>

                        <div class="sku-field">
                            <label for="perpanjangan_alamat">Alamat Lengkap Usaha</label>
                            <input type="text" name="alamat_usaha" id="perpanjangan_alamat" value="<?= esc(old('alamat_usaha')) ?>" placeholder="Lokasi tempat usaha saat ini">
                        </div>

                        <div class="sku-field" style="margin-bottom: 0;">
                            <label for="perpanjangan_deskripsi">Catatan Perubahan Usaha</label>
                            <textarea name="deskripsi_usaha" id="perpanjangan_deskripsi" rows="2" placeholder="Tulis perubahan bila ada (tidak wajib)"><?= esc(old('deskripsi_usaha')) ?></textarea>
                        </div>
                    </div>

                    <div>
                        <div class="sku-section-title">Berkas Persyaratan</div>
                        <?= view('pemohon/_dropzone_list', ['dokumenList' => $dokumenPerpanjangan]) ?>
                    </div>
                </div>

                <div class="sku-form-footer">
                    <a href="<?= base_url('pemohon/dashboard') ?>" class="btn-form-cancel">Batal</a>
                    <button type="submit" class="btn-form-submit">Kirim Perpanjangan</button>
                </div>
            </form>
        <?php endif; ?>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->include('pemohon/_sku_form_scripts') ?>
    <script>
        (function () {
            var skuSelect = document.getElementById('permohonan_sebelumnya_id');
            var masaField = document.getElementById('perpanjangan_masa_berlaku');
            var togglePembaruan = document.getElementById('ada_pembaruan');
            var omsetPerp = document.getElementById('perpanjangan_omset');
            var alamatPerp = document.getElementById('perpanjangan_alamat');
            var deskripsiPerp = document.getElementById('perpanjangan_deskripsi');

            function syncSelectedSku(forceOverwrite) {
                if (!skuSelect) return;
                var opt = skuSelect.options[skuSelect.selectedIndex];
                if (!opt) return;

                if (masaField) {
                    masaField.value = opt.dataset.masa || '-';
                    masaField.classList.toggle('is-expired', opt.dataset.expired === '1');
                    masaField.classList.toggle('is-active', opt.dataset.expired !== '1');
                }

                if (forceOverwrite || !omsetPerp.value) {
                    var rawOmset = opt.dataset.omset || '';
                    omsetPerp.value = rawOmset ? Number(rawOmset).toLocaleString('id-ID') : '';
                }
                if (forceOverwrite || !alamatPerp.value) {
                    alamatPerp.value = opt.dataset.alamat || '';
                }
                if (forceOverwrite || !deskripsiPerp.value) {
                    deskripsiPerp.value = opt.dataset.deskripsi || '';
                }
            }

            function syncPembaruanState() {
                if (!togglePembaruan) return;
                var editable = togglePembaruan.checked;
                [omsetPerp, alamatPerp, deskripsiPerp].forEach(function (el) {
                    if (el) el.readOnly = !editable;
                });
            }

            if (skuSelect) {
                syncSelectedSku(false);
                syncPembaruanState();
                skuSelect.addEventListener('change', function () {
                    syncSelectedSku(true);
                });
            }

            if (togglePembaruan) {
                togglePembaruan.addEventListener('change', syncPembaruanState);
            }
        })();
    </script>
<?= $this->endSection() ?>
