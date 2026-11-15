<?php
$formatTanggalIndo = static function (?string $date): string {
    if (empty($date)) {
        return '-';
    }
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];
    $ts = strtotime($date);
    return $ts === false ? '-' : date('d', $ts) . ' ' . $months[(int) date('n', $ts)] . ' ' . date('Y', $ts);
};

$statusTempatMap = [
    'milik_sendiri' => 'Milik Sendiri',
    'sewa'          => 'Sewa / Kontrak',
    'menumpang'     => 'Menumpang',
    'lainnya'       => 'Lainnya',
];

$isPerpanjangan = ($permohonan['tipe_pengajuan'] ?? 'baru') === 'perpanjangan';
$tabs = $isPerpanjangan
    ? [
        ['key' => 'sku_lama',        'label' => 'Lembar SKU Lama'],
        ['key' => 'pengantar_rt_rw', 'label' => 'Pengantar RT/RW/Dukuh'],
        ['key' => 'ktp',             'label' => 'KTP & Kartu Keluarga'],
        ['key' => 'foto_usaha',      'label' => 'Foto Tempat Usaha'],
        ['key' => 'data_usaha',      'label' => 'Data Usaha'],
    ]
    : [
        ['key' => 'pengantar_rt_rw', 'label' => 'Pengantar RT/RW/Dukuh'],
        ['key' => 'ktp',             'label' => 'KTP-el Pemohon'],
        ['key' => 'kk',              'label' => 'Kartu Keluarga'],
        ['key' => 'foto_usaha',      'label' => 'Foto Tempat Usaha'],
        ['key' => 'data_usaha',      'label' => 'Data Usaha'],
    ];

$masaBerlakuText = $formatTanggalIndo($tanggalSurat) . ' s.d. ' . $formatTanggalIndo($berlakuSampai);
$padukuhanText   = !empty($permohonan['padukuhan']) ? $permohonan['padukuhan'] : 'Condongcatur';
$statusKey       = (string) ($permohonan['status'] ?? 'diajukan');
$canVerify       = in_array($statusKey, ['diajukan', 'verifikasi_staf'], true);
$readonlyMetaMap = [
    'revisi'                     => ['label' => 'Menunggu Revisi Warga', 'bg' => '#fee2e2', 'color' => '#b91c1c'],
    'menunggu_persetujuan_lurah' => ['label' => 'Lolos Verifikasi — Menunggu TTE Lurah', 'bg' => '#e0e7ff', 'color' => '#4338ca'],
    'disetujui'                  => ['label' => '✓ Telah Disahkan Lurah', 'bg' => '#dcfce7', 'color' => '#15803d'],
    'selesai'                    => ['label' => '✓ Selesai & SKU Terbit', 'bg' => '#dcfce7', 'color' => '#15803d'],
    'ditolak'                    => ['label' => 'Ditolak', 'bg' => '#fee2e2', 'color' => '#b91c1c'],
];
$readonlyMeta    = $readonlyMetaMap[$statusKey] ?? ['label' => 'Sudah Diproses', 'bg' => '#f1f5f9', 'color' => '#334155'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Verifikasi Berkas - Sistem SKU') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #e3e6eb;
            color: #0f172a;
            padding: 1.15rem 1.5rem 1.5rem;
        }

        .verify-shell {
            max-width: 1340px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Top Bar */
        .verify-topbar {
            background: #ffffff;
            border-radius: 12px;
            padding: 0.9rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 700;
            color: #4f46e5;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .topbar-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #0f172a;
        }

        .switch-select {
            height: 32px;
            padding: 0 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 9999px;
            background: #f8fafc;
            color: #334155;
            font-size: 0.75rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
        }

        .alert-flash {
            padding: 0.7rem 1rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .alert-flash.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-flash ul {
            margin-left: 1.1rem;
        }

        /* 2-Column Split */
        .verify-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.95fr;
            gap: 1.15rem;
            align-items: stretch;
        }

        .card-panel {
            background: #ffffff;
            border-radius: 14px;
            padding: 1.2rem 1.3rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
            display: flex;
            flex-direction: column;
        }

        /* Left Document Tabs */
        .doc-tabs {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid #eef2f6;
            margin-bottom: 0.9rem;
        }

        .doc-tab {
            border: none;
            background: transparent;
            color: #475569;
            font-size: 0.74rem;
            font-weight: 600;
            padding: 0.5rem 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .doc-tab:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .doc-tab.is-active {
            background: #5246f0;
            color: #ffffff;
        }

        .doc-pane {
            display: none;
            flex: 1;
            flex-direction: column;
        }

        .doc-pane.is-active {
            display: flex;
        }

        .doc-viewer {
            flex: 1;
            min-height: 455px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .doc-viewer-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.55rem 0.9rem;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.74rem;
            color: #475569;
        }

        .doc-viewer-bar a {
            color: #4f46e5;
            font-weight: 600;
            text-decoration: none;
        }

        .doc-viewer-bar a:hover {
            text-decoration: underline;
        }

        .doc-frame {
            width: 100%;
            flex: 1;
            min-height: 410px;
            border: none;
            background: #ffffff;
        }

        .doc-image-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: #f8fafc;
        }

        .doc-image-wrap img {
            max-width: 100%;
            max-height: 410px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            object-fit: contain;
        }

        .doc-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            color: #64748b;
            font-size: 0.82rem;
            gap: 0.35rem;
        }

        .data-usaha-box {
            padding: 1.15rem 1.25rem;
            display: grid;
            grid-template-columns: 155px 1fr;
            gap: 0.65rem 0.9rem;
            font-size: 0.8rem;
            align-content: start;
        }

        .kv-k { color: #64748b; font-weight: 500; }
        .kv-v { color: #0f172a; font-weight: 600; }

        /* Right Draft Form */
        .draft-heading {
            font-size: 0.8rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .draft-form {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .draft-field {
            margin-bottom: 0.85rem;
        }

        .draft-field label {
            display: block;
            font-size: 0.74rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .draft-field input,
        .draft-field textarea {
            width: 100%;
            padding: 0.62rem 0.85rem;
            border: 1.5px solid #cbd5e1;
            border-radius: 9px;
            background: #ffffff;
            color: #0f172a;
            font-size: 0.8rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .draft-field input:focus,
        .draft-field textarea:focus {
            border-color: #5246f0;
            box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.14);
        }

        .draft-field input[readonly] {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            cursor: default;
        }

        .draft-field input.is-nomor {
            color: #4338ca;
            font-weight: 700;
        }

        .draft-field textarea {
            resize: vertical;
            min-height: 92px;
            line-height: 1.45;
        }

        .draft-actions {
            margin-top: auto;
            padding-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .btn-revisi-outline {
            height: 40px;
            padding: 0 1.2rem;
            border-radius: 9999px;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-revisi-outline:hover {
            background: #fee2e2;
        }

        .btn-lolos-solid {
            height: 40px;
            padding: 0 1.4rem;
            border-radius: 9999px;
            border: none;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(82, 70, 240, 0.28);
            transition: background 0.15s ease, transform 0.15s ease;
        }

        .btn-lolos-solid:hover {
            background: #463ae0;
            transform: translateY(-1px);
        }

        @media (max-width: 960px) {
            body {
                padding: 0.9rem;
            }

            .verify-grid {
                grid-template-columns: 1fr;
            }

            .doc-viewer {
                min-height: 340px;
            }
        }
    </style>
</head>
<body>
    <div class="verify-shell">
        <header class="verify-topbar">
            <a href="<?= base_url($canVerify ? 'staf/antrian' : 'staf/dashboard') ?>" class="back-link">
                &larr; <?= $canVerify ? 'Kembali ke Antrian' : 'Kembali ke Dashboard' ?>
            </a>

            <div class="topbar-right">
                <h1 class="topbar-title">
                    Verifikasi Berkas Pemohon: <?= esc($permohonan['kode_pengajuan']) ?>
                    (<?= esc($permohonan['nama_pemohon']) ?> - <?= esc($padukuhanText) ?>)
                </h1>

                <?php if (count($antreanSwitch) > 1): ?>
                    <select class="switch-select" aria-label="Pindah berkas antrian" onchange="if (this.value) window.location.href = this.value;">
                        <?php foreach ($antreanSwitch as $item): ?>
                            <option
                                value="<?= base_url('staf/periksa/' . (int) $item['id']) ?>"
                                <?= (int) $item['id'] === (int) $permohonan['id'] ? 'selected' : '' ?>
                            >
                                <?= esc($item['kode_pengajuan'] . ' - ' . $item['nama_pemohon']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
        </header>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-flash error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert-flash error">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <main class="verify-grid">
            <!-- Panel Kiri: Preview Berkas dengan Tab Interaktif -->
            <section class="card-panel">
                <div class="doc-tabs" role="tablist" aria-label="Berkas Persyaratan Pemohon">
                    <?php foreach ($tabs as $idx => $tab): ?>
                        <button
                            type="button"
                            class="doc-tab <?= $idx === 0 ? 'is-active' : '' ?>"
                            role="tab"
                            aria-selected="<?= $idx === 0 ? 'true' : 'false' ?>"
                            data-doc-tab="<?= esc($tab['key']) ?>"
                        >
                            <?= esc($tab['label']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <?php foreach ($tabs as $idx => $tab): ?>
                    <?php $key = $tab['key']; ?>
                    <div
                        class="doc-pane <?= $idx === 0 ? 'is-active' : '' ?>"
                        role="tabpanel"
                        data-doc-pane="<?= esc($key) ?>"
                    >
                        <div class="doc-viewer">
                            <?php if ($key === 'data_usaha'): ?>
                                <div class="data-usaha-box">
                                    <span class="kv-k">Nama Pemohon</span>
                                    <span class="kv-v"><?= esc($permohonan['nama_pemohon']) ?></span>

                                    <span class="kv-k">NIK</span>
                                    <span class="kv-v"><?= esc($permohonan['nik_pemohon']) ?></span>

                                    <span class="kv-k">No. WhatsApp</span>
                                    <span class="kv-v"><?= esc($permohonan['no_hp'] ?: '-') ?></span>

                                    <span class="kv-k">Nama Usaha</span>
                                    <span class="kv-v"><?= esc($permohonan['nama_usaha']) ?></span>

                                    <span class="kv-k">Bidang Usaha</span>
                                    <span class="kv-v"><?= esc($permohonan['bidang_usaha']) ?></span>

                                    <span class="kv-k">Alamat Usaha</span>
                                    <span class="kv-v"><?= esc($permohonan['alamat_usaha']) ?></span>

                                    <span class="kv-k">Padukuhan</span>
                                    <span class="kv-v"><?= esc($padukuhanText) ?></span>

                                    <span class="kv-k">Lama Usaha</span>
                                    <span class="kv-v"><?= (int) ($permohonan['lama_usaha_tahun'] ?? 0) ?> Tahun</span>

                                    <span class="kv-k">Omset / Bulan</span>
                                    <span class="kv-v">Rp <?= number_format((int) ($permohonan['omset_per_bulan'] ?? 0), 0, ',', '.') ?></span>

                                    <span class="kv-k">Status Tempat</span>
                                    <span class="kv-v"><?= esc($statusTempatMap[$permohonan['status_tempat_usaha'] ?? ''] ?? '-') ?></span>
                                </div>
                            <?php elseif (!empty($dokByJenis[$key])): ?>
                                <?php
                                    $dok     = $dokByJenis[$key];
                                    $dokUrl  = base_url('staf/dokumen/' . (int) $dok['id']);
                                    $mime    = strtolower((string) ($dok['tipe_file'] ?? ''));
                                    $isImage = strpos($mime, 'image/') === 0 || preg_match('/\.(jpe?g|png|webp)$/i', (string) $dok['nama_file']);
                                ?>
                                <div class="doc-viewer-bar">
                                    <span><?= esc($dok['nama_file']) ?> (<?= max(1, (int) round(($dok['ukuran_file'] ?? 0) / 1024)) ?> KB)</span>
                                    <a href="<?= esc($dokUrl) ?>" target="_blank" rel="noopener">Buka Tab Baru &nearr;</a>
                                </div>
                                <?php if ($isImage): ?>
                                    <div class="doc-image-wrap">
                                        <img src="<?= esc($dokUrl) ?>" alt="<?= esc($tab['label']) ?>">
                                    </div>
                                <?php else: ?>
                                    <iframe src="<?= esc($dokUrl) ?>" class="doc-frame" title="<?= esc($tab['label']) ?>"></iframe>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="doc-empty">
                                    <strong>Berkas <?= esc($tab['label']) ?> belum tersedia</strong>
                                    <span>Tidak ada file terunggah pada kategori dokumen ini.</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <!-- Panel Kanan: Form Draft Penerbitan SKU -->
            <section class="card-panel">
                <h2 class="draft-heading">DRAFT PENERBITAN SURAT KETERANGAN USAHA (SKU)</h2>

                <?php if ($canVerify): ?>
                    <form action="<?= base_url('staf/periksa/' . (int) $permohonan['id']) ?>" method="POST" class="draft-form" id="form-verifikasi-sku">
                        <?= csrf_field() ?>
                <?php else: ?>
                    <div class="draft-form">
                <?php endif; ?>

                    <div class="draft-field">
                        <label for="nomor_surat">Nomor Surat Resmi Kalurahan (Otomatis)</label>
                        <input
                            type="text"
                            name="nomor_surat"
                            id="nomor_surat"
                            class="is-nomor"
                            value="<?= esc(old('nomor_surat') ?? $saranNomor) ?>"
                            placeholder="Contoh: 510/142/CDC/SKU/XI/2026"
                            <?= $canVerify ? '' : 'readonly' ?>
                        >
                    </div>

                    <div class="draft-field">
                        <label for="kbli">Klasifikasi Baku Lapangan Usaha</label>
                        <input
                            type="text"
                            name="kbli"
                            id="kbli"
                            value="<?= esc(old('kbli') ?? ($permohonan['kbli'] ?: $permohonan['bidang_usaha'])) ?>"
                            placeholder="Contoh: 56101 - Restoran dan Penyedia Makanan Keliling"
                            <?= $canVerify ? '' : 'readonly' ?>
                        >
                    </div>

                    <div class="draft-field">
                        <label for="masa_berlaku">Masa Berlaku SKU</label>
                        <input
                            type="text"
                            id="masa_berlaku"
                            value="<?= esc($masaBerlakuText) ?>"
                            readonly
                        >
                    </div>

                    <div class="draft-field">
                        <label for="catatan">Catatan Verifikator Staf Pelayanan</label>
                        <textarea
                            name="catatan"
                            id="catatan"
                            placeholder="<?= $canVerify ? 'Tulis hasil pemeriksaan berkas atau catatan perbaikan jika meminta revisi warga...' : '-' ?>"
                            <?= $canVerify ? '' : 'readonly' ?>
                        ><?= esc(old('catatan') ?? ($permohonan['catatan_revisi'] ?? '')) ?></textarea>
                    </div>

                    <div class="draft-actions">
                        <?php if ($canVerify): ?>
                            <button type="submit" name="aksi" value="revisi" class="btn-revisi-outline" id="btn-minta-revisi">
                                Minta Revisi Warga
                            </button>
                            <button type="submit" name="aksi" value="lolos" class="btn-lolos-solid" id="btn-lolos-lurah">
                                &#10003; Loloskan &amp; Kirim ke Lurah untuk TTE
                            </button>
                        <?php else: ?>
                            <span style="display:inline-flex;align-items:center;padding:0.55rem 1.05rem;border-radius:9999px;font-size:0.78rem;font-weight:700;background:<?= esc($readonlyMeta['bg']) ?>;color:<?= esc($readonlyMeta['color']) ?>;">
                                <?= esc($readonlyMeta['label']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php if ($canVerify): ?>
                    </form>
                <?php else: ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script>
        (function () {
            var tabs = document.querySelectorAll('[data-doc-tab]');
            var panes = document.querySelectorAll('[data-doc-pane]');

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var target = this.dataset.docTab;

                    tabs.forEach(function (t) {
                        var active = t === tab;
                        t.classList.toggle('is-active', active);
                        t.setAttribute('aria-selected', active ? 'true' : 'false');
                    });

                    panes.forEach(function (pane) {
                        pane.classList.toggle('is-active', pane.dataset.docPane === target);
                    });
                });
            });
        })();
    </script>
</body>
</html>
