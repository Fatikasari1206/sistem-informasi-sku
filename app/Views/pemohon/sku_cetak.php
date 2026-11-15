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
$namaLurah     = (string) ($pengaturan['nama_lurah'] ?: 'Drs. H. Reno Candra, M.Si');
$nipLurah      = (string) ($pengaturan['nip_lurah'] ?: '19700315 199503 1 002');
$bsreTokenText = !empty($permohonan['qr_token'])
    ? 'BSrE BSSN Token: ' . $permohonan['qr_token']
    : 'BSrE BSSN Terverifikasi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Cetak Surat Keterangan Usaha (SKU)') ?></title>
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
            padding: 1.25rem 1.5rem 2rem;
        }

        .print-shell {
            max-width: 880px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .print-topbar {
            background: #ffffff;
            border-radius: 12px;
            padding: 0.85rem 1.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        }

        .back-link {
            font-size: 0.82rem;
            font-weight: 700;
            color: #4f46e5;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .btn-print {
            height: 36px;
            padding: 0 1.25rem;
            border: none;
            border-radius: 9999px;
            background: #5246f0;
            color: #ffffff;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(82, 70, 240, 0.25);
        }

        .letter-sheet {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 2.35rem 2.75rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        }

        .kop-surat {
            text-align: center;
            padding-bottom: 0.85rem;
            border-bottom: 2.5px solid #0f172a;
            margin-bottom: 1.25rem;
        }

        .kop-line-1, .kop-line-2 {
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: #0f172a;
        }

        .kop-line-3 {
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #0f172a;
            margin-top: 0.14rem;
        }

        .kop-contact {
            font-size: 0.69rem;
            color: #64748b;
            margin-top: 0.32rem;
        }

        .surat-title-block {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .surat-title {
            font-size: 0.94rem;
            font-weight: 800;
            color: #0f172a;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .surat-nomor {
            font-size: 0.78rem;
            font-weight: 600;
            color: #475569;
            margin-top: 0.28rem;
        }

        .surat-para {
            font-size: 0.8rem;
            line-height: 1.65;
            color: #1e293b;
            margin-bottom: 0.85rem;
        }

        .bio-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            font-size: 0.8rem;
        }

        .bio-table td {
            padding: 0.28rem 0;
            vertical-align: top;
            color: #1e293b;
        }

        .bio-label {
            width: 195px;
            color: #475569;
            font-weight: 500;
        }

        .bio-sep {
            width: 18px;
            text-align: center;
        }

        .bio-val-strong {
            font-weight: 700;
            color: #0f172a;
        }

        .validity-box {
            margin: 1.15rem 0 1.4rem;
            padding: 0.78rem 1rem;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.78rem;
            color: #1e293b;
            line-height: 1.55;
        }

        .tte-sign-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.25rem;
        }

        .tte-sign-box {
            width: 340px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            background: #f8fafc;
        }

        .tte-sign-head {
            font-size: 0.7rem;
            font-weight: 600;
            color: #475569;
            line-height: 1.45;
            margin-bottom: 0.6rem;
        }

        .tte-sign-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .qr-box {
            width: 54px;
            height: 54px;
            border-radius: 8px;
            background: #065f46;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .sign-name {
            font-size: 0.76rem;
            font-weight: 800;
            color: #0f172a;
        }

        .sign-meta {
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 0.14rem;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .print-topbar {
                display: none !important;
            }

            .letter-sheet {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-shell">
        <header class="print-topbar">
            <a href="<?= base_url('pemohon/dashboard') ?>" class="back-link">&larr; Kembali ke Dashboard Pemohon</a>
            <button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
        </header>

        <main class="letter-sheet">
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
                        <div class="qr-box">
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
        </main>
    </div>
</body>
</html>
