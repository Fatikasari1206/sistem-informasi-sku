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

$isPendingTte  = ($permohonan['status'] ?? '') === 'menunggu_persetujuan_lurah';
$isSigned      = in_array($permohonan['status'] ?? '', ['disetujui', 'selesai'], true);
$tanggalSurat  = !empty($permohonan['tanggal_surat']) ? $permohonan['tanggal_surat'] : date('Y-m-d');
$berlakuSampai = !empty($permohonan['berlaku_sampai'])
    ? $permohonan['berlaku_sampai']
    : date('Y-m-d', strtotime($tanggalSurat . ' +1 year'));

$lamaTahun  = (int) ($permohonan['lama_usaha_tahun'] ?? 0);
$tahunMulai = (int) date('Y', strtotime($tanggalSurat)) - $lamaTahun;
$lamaText   = $lamaTahun > 0
    ? "{$tahunMulai} (Telah berjalan {$lamaTahun} tahun)"
    : "{$tahunMulai} (Usaha baru berjalan)";

$rtRwDomisili = (!empty($permohonan['domisili_rt']) && !empty($permohonan['domisili_rw']))
    ? 'RT ' . $permohonan['domisili_rt'] . ' / RW ' . $permohonan['domisili_rw'] . ', '
    : '';
$baseDomisili = !empty($permohonan['domisili_alamat'])
    ? $permohonan['domisili_alamat']
    : (!empty($permohonan['alamat_pemohon'])
        ? $permohonan['alamat_pemohon']
        : (!empty($permohonan['domisili_padukuhan']) ? 'Padukuhan ' . $permohonan['domisili_padukuhan'] . ', Condongcatur, Depok, Sleman' : 'Kalurahan Condongcatur'));
$alamatDomisili = $rtRwDomisili . $baseDomisili;

$padukuhanUsaha = !empty($permohonan['padukuhan']) ? 'Padukuhan ' . $permohonan['padukuhan'] : 'Kalurahan Condongcatur';
$rtRwUsaha = (!empty($permohonan['rt_usaha']) && !empty($permohonan['rw_usaha']))
    ? ', RT ' . $permohonan['rt_usaha'] . ' / RW ' . $permohonan['rw_usaha']
    : '';
$lokasiUsaha = $permohonan['alamat_usaha'] . $rtRwUsaha . ', ' . $padukuhanUsaha . ', Condongcatur';

$bidangKbli = trim((string) ($permohonan['bidang_usaha'] ?? ''));
$kbliVal    = trim((string) ($permohonan['kbli'] ?? ''));
if ($kbliVal !== '' && strcasecmp($kbliVal, $bidangKbli) !== 0) {
    $bidangKbli .= ' (' . $kbliVal . ')';
}

$namaKalurahan = strtoupper((string) ($pengaturan['nama_kalurahan'] ?? 'Condongcatur'));
$namaKapanewon = strtoupper((string) ($pengaturan['nama_kapanewon'] ?? 'Depok'));
$kabupatenKota = strtoupper((string) ($pengaturan['kabupaten_kota'] ?? 'Kabupaten Sleman'));
$alamatKantor  = (string) ($pengaturan['alamat_kantor'] ?? 'Jl. Ring Road Utara, Condongcatur, Depok, Sleman');
$kodePos       = (string) ($pengaturan['kode_pos'] ?? '55283');
$website       = (string) ($pengaturan['website'] ?? 'https://condongcatur.desa.id');
$email         = (string) ($pengaturan['email'] ?? 'kalurahan@condongcatur.desa.id');
$namaLurah     = (string) ($pengaturan['nama_lurah'] ?: session()->get('namaLengkap') ?: 'Lurah Condongcatur');
$nipLurah      = (string) ($pengaturan['nip_lurah'] ?: session()->get('nip') ?: '-');
$bsreTokenText = !empty($permohonan['qr_token'])
    ? 'BSrE BSSN Token: ' . $permohonan['qr_token']
    : 'BSrE BSSN Ready (Token diterbitkan saat disahkan)';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Portal Pengesahan SKU (TTE) - Sistem SKU') ?></title>
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
            padding: 1.15rem 1.5rem 1.65rem;
        }

        .tte-shell {
            max-width: 1320px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tte-topbar {
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

        .topbar-title {
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.03em;
            color: #1e293b;
            text-transform: uppercase;
        }

        .flash-banner {
            padding: 0.78rem 1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .flash-banner.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .flash-banner.success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .tte-grid {
            display: grid;
            grid-template-columns: 310px 1fr;
            gap: 1.15rem;
            align-items: start;
        }

        .queue-panel,
        .letter-panel {
            background: #ffffff;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        }

        .queue-heading {
            font-size: 0.73rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #334155;
            text-transform: uppercase;
            margin-bottom: 0.9rem;
        }

        .queue-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .queue-item {
            display: block;
            text-decoration: none;
            padding: 0.82rem 0.92rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: border-color 0.15s ease, background-color 0.15s ease, transform 0.15s ease;
        }

        .queue-item:hover {
            border-color: #a5b4fc;
            background: #ffffff;
            transform: translateY(-1px);
        }

        .queue-item.is-active {
            border: 1.5px solid #5246f0;
            background: #f5f3ff;
        }

        .queue-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #0f172a;
        }

        .queue-sub {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 0.2rem;
        }

        .queue-nomor {
            font-size: 0.71rem;
            font-weight: 600;
            color: #4f46e5;
            margin-top: 0.3rem;
        }

        .queue-verified {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 700;
            color: #059669;
            margin-top: 0.28rem;
        }

        .queue-empty {
            padding: 1.2rem 0.75rem;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            text-align: center;
            font-size: 0.76rem;
            color: #64748b;
        }

        /* Letter Document Sheet */
        .letter-sheet {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 2rem 2.35rem;
            background: #ffffff;
        }

        .kop-surat {
            text-align: center;
            padding-bottom: 0.85rem;
            border-bottom: 2.5px solid #0f172a;
            margin-bottom: 1.25rem;
        }

        .kop-line-1 {
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: #0f172a;
        }

        .kop-line-2 {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: #0f172a;
            margin-top: 0.1rem;
        }

        .kop-line-3 {
            font-size: 0.98rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #0f172a;
            margin-top: 0.14rem;
        }

        .kop-contact {
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 0.32rem;
        }

        .surat-title-block {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .surat-title {
            font-size: 0.92rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.02em;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .surat-nomor {
            font-size: 0.78rem;
            font-weight: 600;
            color: #334155;
            margin-top: 0.35rem;
        }

        .surat-para {
            font-size: 0.8rem;
            line-height: 1.6;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .bio-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.35rem 0 0.95rem;
        }

        .bio-table td {
            padding: 0.22rem 0.2rem;
            font-size: 0.79rem;
            vertical-align: top;
            color: #0f172a;
        }

        .bio-label {
            width: 175px;
            color: #475569;
        }

        .bio-sep {
            width: 14px;
            color: #475569;
        }

        .bio-val-strong {
            font-weight: 700;
        }

        .validity-box {
            margin-top: 0.95rem;
            padding: 0.68rem 0.9rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #f8fafc;
            font-size: 0.76rem;
            color: #1e293b;
            line-height: 1.5;
        }

        .validity-box strong {
            color: #0f172a;
        }

        .tte-sign-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.35rem;
        }

        .tte-sign-box {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.78rem 0.95rem;
            background: #f8fafc;
            min-width: 285px;
        }

        .tte-sign-head {
            font-size: 0.68rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .tte-sign-content {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .qr-box {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            background: #1e293b;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 0.62rem;
            font-weight: 800;
            line-height: 1.15;
            flex-shrink: 0;
        }

        .qr-box.is-signed {
            background: #15803d;
        }

        .sign-name {
            font-size: 0.73rem;
            font-weight: 800;
            color: #0f172a;
        }

        .sign-meta {
            font-size: 0.66rem;
            color: #475569;
            margin-top: 0.12rem;
        }

        .tte-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.7rem;
            margin-top: 1.25rem;
            flex-wrap: wrap;
        }

        .btn-return {
            height: 40px;
            padding: 0 1.2rem;
            border-radius: 9999px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 0.79rem;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .btn-return:hover {
            background: #fee2e2;
        }

        .btn-sahkan {
            height: 40px;
            padding: 0 1.45rem;
            border-radius: 9999px;
            border: none;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.79rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(82, 70, 240, 0.24);
            transition: opacity 0.15s ease, transform 0.15s ease;
        }

        .btn-sahkan:hover {
            opacity: 0.94;
            transform: translateY(-1px);
        }

        .btn-print {
            height: 40px;
            padding: 0 1.35rem;
            border-radius: 9999px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            font-size: 0.79rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #f8fafc;
        }

        .signed-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.95rem;
            border-radius: 9999px;
            background: #dcfce7;
            color: #15803d;
            font-size: 0.76rem;
            font-weight: 700;
        }

        /* Return Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 9999;
        }

        .modal-backdrop.is-open {
            display: flex;
        }

        .modal-card {
            width: 100%;
            max-width: 460px;
            background: #ffffff;
            border-radius: 14px;
            padding: 1.25rem 1.35rem;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.25);
        }

        .modal-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.3rem;
        }

        .modal-desc {
            font-size: 0.76rem;
            color: #64748b;
            margin-bottom: 0.85rem;
        }

        .modal-textarea {
            width: 100%;
            min-height: 92px;
            padding: 0.65rem 0.8rem;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 0.8rem;
            color: #0f172a;
            outline: none;
            resize: vertical;
        }

        .modal-textarea:focus {
            border-color: #5246f0;
            box-shadow: 0 0 0 3px rgba(82, 70, 240, 0.12);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
            margin-top: 0.95rem;
        }

        .btn-modal-cancel {
            height: 36px;
            padding: 0 1rem;
            border-radius: 9999px;
            border: none;
            background: #f1f5f9;
            color: #334155;
            font-size: 0.76rem;
            font-weight: 600;
            cursor: pointer;
        }

        @media (max-width: 980px) {
            .tte-grid {
                grid-template-columns: 1fr;
            }

            .letter-sheet {
                padding: 1.35rem 1.15rem;
            }
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .tte-topbar,
            .queue-panel,
            .tte-actions,
            .flash-banner {
                display: none !important;
            }

            .tte-grid {
                grid-template-columns: 1fr;
            }

            .letter-panel {
                box-shadow: none;
                padding: 0;
            }

            .letter-sheet {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="tte-shell">
        <header class="tte-topbar">
            <a href="<?= base_url($isSigned ? 'lurah/arsip' : 'lurah/antrian') ?>" class="back-link">&larr; Kembali ke Antrian</a>
            <div class="topbar-title">PORTAL PENGESAHAN SURAT KETERANGAN USAHA (SKU) - LURAH <?= esc($namaKalurahan) ?></div>
        </header>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-banner error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-banner success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <div class="tte-grid">
            <aside class="queue-panel">
                <div class="queue-heading">BERKAS SIAP TTE DIGITAL (<?= count($antreanTte) ?>)</div>

                <?php if (empty($antreanTte)): ?>
                    <div class="queue-empty">
                        Tidak ada berkas lain dalam antrian TTE Digital.
                    </div>
                <?php else: ?>
                    <div class="queue-list">
                        <?php foreach ($antreanTte as $item): ?>
                            <?php
                                $isActive  = (int) $item['id'] === (int) $permohonan['id'];
                                $itemDukuh = !empty($item['padukuhan']) ? 'Padukuhan ' . $item['padukuhan'] : 'Condongcatur';
                            ?>
                            <a href="<?= base_url('lurah/tte/' . (int) $item['id']) ?>" class="queue-item <?= $isActive ? 'is-active' : '' ?>">
                                <div class="queue-name"><?= esc($item['nama_pemohon']) ?></div>
                                <div class="queue-sub"><?= esc($item['nama_usaha']) ?> - <?= esc($itemDukuh) ?></div>
                                <div class="queue-nomor">No: <?= esc($item['nomor_surat'] ?: $item['kode_pengajuan']) ?></div>
                                <?php if ($isActive): ?>
                                    <span class="queue-verified">&#10003; Diverifikasi Staf</span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </aside>

            <main class="letter-panel">
                <div class="letter-sheet">
                    <div class="kop-surat">
                        <div class="kop-line-1">PEMERINTAH <?= esc($kabupatenKota) ?></div>
                        <div class="kop-line-2">KAPANEWON <?= esc($namaKapanewon) ?></div>
                        <div class="kop-line-3">KALURAHAN <?= esc($namaKalurahan) ?></div>
                        <div class="kop-contact">
                            Alamat: <?= esc($alamatKantor) ?>, <?= esc($kodePos) ?>
                            &bull; Laman: <?= esc($website) ?> &bull; Pos-el: <?= esc($email) ?>
                        </div>
                    </div>

                    <div class="surat-title-block">
                        <div class="surat-title">SURAT KETERANGAN USAHA (SKU)</div>
                        <div class="surat-nomor">Nomor: <?= esc($permohonan['nomor_surat'] ?: $permohonan['kode_pengajuan']) ?></div>
                    </div>

                    <p class="surat-para">
                        Yang bertanda tangan di bawah ini, Lurah <?= esc(ucwords(strtolower($namaKalurahan))) ?>,
                        Kapanewon <?= esc(ucwords(strtolower($namaKapanewon))) ?>,
                        <?= esc(ucwords(strtolower($kabupatenKota))) ?>, menerangkan dengan sebenarnya bahwa:
                    </p>

                    <table class="bio-table">
                        <tr>
                            <td class="bio-label">Nama Lengkap</td>
                            <td class="bio-sep">:</td>
                            <td class="bio-val-strong"><?= esc($permohonan['nama_pemohon']) ?></td>
                        </tr>
                        <tr>
                            <td class="bio-label">NIK (KTP-el)</td>
                            <td class="bio-sep">:</td>
                            <td><?= esc($permohonan['nik_pemohon']) ?></td>
                        </tr>
                        <tr>
                            <td class="bio-label">Alamat Domisili</td>
                            <td class="bio-sep">:</td>
                            <td><?= esc($alamatDomisili) ?></td>
                        </tr>
                    </table>

                    <p class="surat-para">
                        Adalah benar yang bersangkutan memiliki dan mengelola kegiatan usaha sebagai berikut:
                    </p>

                    <table class="bio-table">
                        <tr>
                            <td class="bio-label">Nama Usaha</td>
                            <td class="bio-sep">:</td>
                            <td class="bio-val-strong"><?= esc($permohonan['nama_usaha']) ?></td>
                        </tr>
                        <tr>
                            <td class="bio-label">Bidang / Jenis Usaha</td>
                            <td class="bio-sep">:</td>
                            <td><?= esc($bidangKbli) ?></td>
                        </tr>
                        <tr>
                            <td class="bio-label">Lokasi Alamat Usaha</td>
                            <td class="bio-sep">:</td>
                            <td><?= esc($lokasiUsaha) ?></td>
                        </tr>
                        <tr>
                            <td class="bio-label">Tahun Mulai Usaha</td>
                            <td class="bio-sep">:</td>
                            <td><?= esc($lamaText) ?></td>
                        </tr>
                        <tr>
                            <td class="bio-label">Status Tempat</td>
                            <td class="bio-sep">:</td>
                            <td><?= esc($statusTempatMap[$permohonan['status_tempat_usaha']] ?? $permohonan['status_tempat_usaha']) ?></td>
                        </tr>
                    </table>

                    <div class="validity-box">
                        Surat Keterangan Usaha ini berlaku selama 1 (satu) tahun terhitung sejak tanggal ditetapkan:
                        <strong><?= esc($formatTanggalIndo($tanggalSurat)) ?> s.d. <?= esc($formatTanggalIndo($berlakuSampai)) ?>.</strong>
                    </div>

                    <div class="tte-sign-row">
                        <div class="tte-sign-box">
                            <div class="tte-sign-head">
                                Ditandatangani secara elektronik oleh:<br>
                                LURAH <?= esc($namaKalurahan) ?>
                            </div>
                            <div class="tte-sign-content">
                                <div class="qr-box <?= $isSigned ? 'is-signed' : '' ?>">
                                    <span>QR</span>
                                    <span>BSrE</span>
                                </div>
                                <div>
                                    <div class="sign-name"><?= esc(strtoupper($namaLurah)) ?></div>
                                    <div class="sign-meta">NIP. <?= esc($nipLurah) ?></div>
                                    <div class="sign-meta"><?= esc($bsreTokenText) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($isPendingTte): ?>
                    <div class="tte-actions">
                        <button type="button" class="btn-return" id="btnOpenReturnModal">Kembalikan ke Staf</button>
                        <form action="<?= base_url('lurah/tte/' . (int) $permohonan['id']) ?>" method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="aksi" value="sahkan">
                            <button type="submit" class="btn-sahkan">Sahkan TTE Digital</button>
                        </form>
                    </div>
                <?php elseif ($isSigned): ?>
                    <div class="tte-actions">
                        <span class="signed-pill">&#10003; Telah Disahkan Secara Elektronik</span>
                        <button type="button" class="btn-print" onclick="window.print()">Cetak Surat</button>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <?php if ($isPendingTte): ?>
        <div class="modal-backdrop" id="returnModal" role="dialog" aria-modal="true" aria-labelledby="returnModalTitle">
            <div class="modal-card">
                <div class="modal-title" id="returnModalTitle">Kembalikan ke Staf Pelayanan</div>
                <p class="modal-desc">Berikan catatan perbaikan agar Staf Pelayanan dapat memeriksa ulang draft surat ini.</p>
                <form action="<?= base_url('lurah/tte/' . (int) $permohonan['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="aksi" value="kembalikan">
                    <textarea
                        name="catatan"
                        class="modal-textarea"
                        placeholder="Contoh: Mohon sesuaikan kode KBLI dan pastikan penulisan alamat usaha lengkap..."
                        required><?= esc(old('catatan')) ?></textarea>
                    <div class="modal-actions">
                        <button type="button" class="btn-modal-cancel" id="btnCloseReturnModal">Batal</button>
                        <button type="submit" class="btn-return">Kirim ke Staf</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            (function () {
                var modal = document.getElementById('returnModal');
                var openBtn = document.getElementById('btnOpenReturnModal');
                var closeBtn = document.getElementById('btnCloseReturnModal');
                if (!modal || !openBtn || !closeBtn) return;

                openBtn.addEventListener('click', function () {
                    modal.classList.add('is-open');
                });
                closeBtn.addEventListener('click', function () {
                    modal.classList.remove('is-open');
                });
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) modal.classList.remove('is-open');
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') modal.classList.remove('is-open');
                });
            })();
        </script>
    <?php endif; ?>
</body>
</html>
