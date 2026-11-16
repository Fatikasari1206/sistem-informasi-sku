<?php if (($part ?? 'body') === 'styles'): ?>
        .letter-sheet {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 2rem 2.35rem;
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
            width: 185px;
            color: #475569;
        }

        .bio-sep {
            width: 16px;
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
            min-width: 295px;
        }

        .tte-sign-head {
            font-size: 0.68rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
            line-height: 1.45;
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
<?php else: ?>
<?php
$statusTempatMap = [
    'milik_sendiri' => 'Milik Sendiri',
    'sewa'          => 'Sewa / Kontrak',
    'menumpang'     => 'Menumpang',
    'lainnya'       => 'Lainnya',
];

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
        <strong><?= esc(format_tanggal_indo($tanggalSurat, true)) ?> s.d. <?= esc(format_tanggal_indo($berlakuSampai, true)) ?>.</strong>
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
<?php endif; ?>
