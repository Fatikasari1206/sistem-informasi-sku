<?php

namespace App\Controllers\Staf;

use App\Controllers\BaseController;
use App\Models\DokumenPersyaratanModel;
use App\Models\PengaturanModel;
use App\Models\PermohonanSkuModel;
use App\Models\RiwayatStatusModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $skuModel = new PermohonanSkuModel();

        $stats = [
            'total_sku'        => $skuModel->countAllResults(),
            'perlu_verifikasi' => $skuModel->whereIn('status', ['diajukan', 'verifikasi_staf'])->countAllResults(),
            'siap_tte'         => $skuModel->countByStatus('menunggu_persetujuan_lurah'),
        ];

        $daftarSku = $skuModel->select(
            "permohonan_sku.*, COALESCE(NULLIF(permohonan_sku.padukuhan_usaha, ''), master_penduduk.padukuhan, '-') AS padukuhan",
            false
        )
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->orderBy("CASE permohonan_sku.status WHEN 'diajukan' THEN 0 WHEN 'verifikasi_staf' THEN 1 WHEN 'revisi' THEN 2 WHEN 'menunggu_persetujuan_lurah' THEN 3 ELSE 4 END", 'ASC', false)
            ->orderBy('permohonan_sku.created_at', 'DESC')
            ->findAll();

        return view('staf/dashboard', [
            'title'         => 'Dashboard Staf Pelayanan - Sistem SKU',
            'logoutLabel'   => 'Keluar Sistem',
            'stats'         => $stats,
            'daftarSku'     => $daftarSku,
            'padukuhanList' => self::PADUKUHAN_LIST,
        ]);
    }

    public function antrian()
    {
        $antrean = $this->fetchPermohonanWithDocCount(['diajukan', 'verifikasi_staf'], 'ASC');

        return view('staf/antrian', [
            'title'         => 'Antrian Masuk Staf - Sistem SKU',
            'logoutLabel'   => 'Keluar Sistem',
            'antrean'       => $antrean,
            'padukuhanList' => self::PADUKUHAN_LIST,
        ]);
    }

    public function verifikasi()
    {
        $skuModel = new PermohonanSkuModel();
        $first    = $skuModel->whereIn('status', ['diajukan', 'verifikasi_staf', 'revisi'])
            ->orderBy('created_at', 'ASC')
            ->first();

        if ($first) {
            return redirect()->to('/staf/periksa/' . (int) $first['id']);
        }

        return view('staf/verifikasi', [
            'title'         => 'Verifikasi Berkas - Sistem SKU',
            'logoutLabel'   => 'Keluar Sistem',
            'antrean'       => [],
            'padukuhanList' => self::PADUKUHAN_LIST,
        ]);
    }

    public function periksa(int $id)
    {
        $skuModel        = new PermohonanSkuModel();
        $dokumenModel    = new DokumenPersyaratanModel();
        $riwayatModel    = new RiwayatStatusModel();
        $pengaturanModel = new PengaturanModel();

        $permohonan = $skuModel->select('permohonan_sku.*, COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan')
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->find($id);

        if (!$permohonan) {
            return redirect()->to('/staf/dashboard')->with('error', 'Data permohonan SKU tidak ditemukan.');
        }

        if ($permohonan['status'] === 'diajukan') {
            $userId = (int) session()->get('userId');
            $skuModel->update($id, [
                'status'  => 'verifikasi_staf',
                'staf_id' => $userId,
            ]);
            $riwayatModel->catat($id, 'diajukan', 'verifikasi_staf', $userId, 'Berkas sedang diperiksa oleh Staf Pelayanan.');
            $permohonan['status'] = 'verifikasi_staf';
        }

        $dokByJenis = [];
        foreach ($dokumenModel->getByPermohonanId($id) as $dok) {
            $dokByJenis[$dok['jenis_dokumen']] = $dok;
        }

        $pengaturan    = $pengaturanModel->getPengaturan();
        $formatNomor   = $pengaturan['format_nomor_surat'] ?? '503/{nomor}/SKU/{bulan_romawi}/{tahun}';
        $saranNomor    = !empty($permohonan['nomor_surat']) ? $permohonan['nomor_surat'] : $skuModel->generateNomorSurat($formatNomor);
        $tanggalSurat  = !empty($permohonan['tanggal_surat']) ? $permohonan['tanggal_surat'] : date('Y-m-d');
        $berlakuSampai = !empty($permohonan['berlaku_sampai']) ? $permohonan['berlaku_sampai'] : date('Y-m-d', strtotime($tanggalSurat . ' +1 year'));

        $antreanSwitch = $skuModel->select('permohonan_sku.id, permohonan_sku.kode_pengajuan, permohonan_sku.nama_pemohon, COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan')
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->whereIn('permohonan_sku.status', ['diajukan', 'verifikasi_staf', 'revisi'])
            ->orderBy('permohonan_sku.created_at', 'ASC')
            ->findAll(25);

        return view('staf/periksa', [
            'title'         => 'Verifikasi Berkas & Input Draft SKU - Sistem SKU',
            'permohonan'    => $permohonan,
            'dokByJenis'    => $dokByJenis,
            'saranNomor'    => $saranNomor,
            'tanggalSurat'  => $tanggalSurat,
            'berlakuSampai' => $berlakuSampai,
            'antreanSwitch' => $antreanSwitch,
        ]);
    }

    public function prosesVerifikasi(int $id)
    {
        $skuModel     = new PermohonanSkuModel();
        $dokumenModel = new DokumenPersyaratanModel();
        $riwayatModel = new RiwayatStatusModel();

        $permohonan = $skuModel->find($id);
        if (!$permohonan) {
            return redirect()->to('/staf/dashboard')->with('error', 'Permohonan tidak ditemukan.');
        }

        if (!in_array($permohonan['status'], ['diajukan', 'verifikasi_staf'], true)) {
            return redirect()->to('/staf/periksa/' . $id)
                ->with('error', 'Permohonan ini sudah diproses dan tidak dapat diubah lagi.');
        }

        $aksi       = (string) $this->request->getPost('aksi');
        $nomorSurat = trim((string) $this->request->getPost('nomor_surat'));
        $kbli       = trim((string) $this->request->getPost('kbli'));
        $catatan    = trim((string) $this->request->getPost('catatan'));
        $userId     = (int) session()->get('userId');
        $now        = date('Y-m-d H:i:s');
        $statusLama = $permohonan['status'];

        if ($aksi === 'lolos') {
            $rules = [
                'nomor_surat' => 'required|max_length[100]',
                'kbli'        => 'required|max_length[150]',
            ];
            $messages = [
                'nomor_surat' => ['required' => 'Nomor surat resmi kalurahan wajib diisi.'],
                'kbli'        => ['required' => 'Klasifikasi Baku Lapangan Usaha (KBLI) wajib diisi.'],
            ];

            if (!$this->validate($rules, $messages)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $tanggalSurat  = !empty($permohonan['tanggal_surat']) ? $permohonan['tanggal_surat'] : date('Y-m-d');
            $berlakuSampai = !empty($permohonan['berlaku_sampai']) ? $permohonan['berlaku_sampai'] : date('Y-m-d', strtotime($tanggalSurat . ' +1 year'));
            $note          = $catatan !== '' ? $catatan : 'Berkas lengkap, lolos verifikasi staf, dan diteruskan ke Lurah untuk TTE.';

            $skuModel->update($id, [
                'status'            => 'menunggu_persetujuan_lurah',
                'nomor_surat'       => $nomorSurat,
                'kbli'              => $kbli,
                'tanggal_surat'     => $tanggalSurat,
                'berlaku_sampai'    => $berlakuSampai,
                'staf_id'           => $userId,
                'diverifikasi_pada' => $now,
                'catatan_revisi'    => null,
            ]);
            $dokumenModel->where('permohonan_id', $id)->set(['status_verifikasi' => 'valid'])->update();
            $riwayatModel->catat($id, $statusLama, 'menunggu_persetujuan_lurah', $userId, $note);

            return redirect()->to('/staf/dashboard')->with('success', "Permohonan {$permohonan['kode_pengajuan']} lolos verifikasi dan dikirim ke Lurah untuk TTE.");
        }

        if ($aksi === 'revisi') {
            if ($catatan === '') {
                return redirect()->back()->withInput()->with('error', 'Tuliskan catatan revisi agar pemohon mengetahui hal yang perlu diperbaiki.');
            }

            $updateData = [
                'status'         => 'revisi',
                'staf_id'        => $userId,
                'catatan_revisi' => $catatan,
            ];
            if ($nomorSurat !== '') {
                $updateData['nomor_surat'] = $nomorSurat;
            }
            if ($kbli !== '') {
                $updateData['kbli'] = $kbli;
            }

            $skuModel->update($id, $updateData);
            $riwayatModel->catat($id, $statusLama, 'revisi', $userId, $catatan);

            return redirect()->to('/staf/dashboard')->with('success', "Permohonan {$permohonan['kode_pengajuan']} dikembalikan ke warga untuk revisi.");
        }

        return redirect()->back()->with('error', 'Tindakan verifikasi tidak dikenali.');
    }

    public function dokumen(int $dokId)
    {
        $dokumenModel = new DokumenPersyaratanModel();
        $dok          = $dokumenModel->find($dokId);

        if (!$dok) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        $baseDir = realpath(WRITEPATH . 'uploads');
        $absPath = realpath(WRITEPATH . $dok['file_path']);
        if (!$baseDir || !$absPath || strpos($absPath, $baseDir) !== 0 || !is_file($absPath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File dokumen tidak tersedia di server.');
        }

        $mime = $dok['tipe_file'] ?: 'application/octet-stream';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($dok['nama_file']) . '"')
            ->setBody((string) file_get_contents($absPath));
    }

    public function penomoran()
    {
        $skuModel        = new PermohonanSkuModel();
        $pengaturanModel = new PengaturanModel();

        $pengaturan = $pengaturanModel->getPengaturan();
        $format     = $pengaturan['format_nomor_surat'] ?? '503/{nomor}/SKU/{bulan_romawi}/{tahun}';
        $saranNomor = $skuModel->generateNomorSurat($format);

        $daftarSku = $skuModel->select('permohonan_sku.*, COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan')
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->whereIn('permohonan_sku.status', ['menunggu_persetujuan_lurah', 'disetujui', 'selesai'])
            ->orderBy('permohonan_sku.diverifikasi_pada', 'DESC')
            ->findAll(50);

        return view('staf/penomoran', [
            'title'       => 'Penerbitan No. SKU - Sistem SKU',
            'logoutLabel' => 'Keluar Sistem',
            'daftarSku'   => $daftarSku,
            'saranNomor'  => $saranNomor,
        ]);
    }

    public function terbitkanNomor(int $id)
    {
        $skuModel   = new PermohonanSkuModel();
        $permohonan = $skuModel->find($id);

        if (!$permohonan) {
            return redirect()->to('/staf/penomoran')->with('error', 'Data permohonan tidak ditemukan.');
        }

        $nomorSurat   = trim((string) $this->request->getPost('nomor_surat'));
        $tanggalSurat = trim((string) $this->request->getPost('tanggal_surat')) ?: date('Y-m-d');

        if ($nomorSurat === '') {
            return redirect()->back()->with('error', 'Nomor surat SKU wajib diisi.');
        }

        $skuModel->update($id, [
            'nomor_surat'   => $nomorSurat,
            'tanggal_surat' => $tanggalSurat,
        ]);

        return redirect()->to('/staf/penomoran')->with('success', "Nomor surat {$nomorSurat} berhasil disimpan untuk {$permohonan['kode_pengajuan']}.");
    }

    private function fetchPermohonanWithDocCount(array $statuses, string $orderDir): array
    {
        $skuModel = new PermohonanSkuModel();

        return $skuModel->select(
            'permohonan_sku.*, '
            . 'COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan, '
            . 'COUNT(dokumen_persyaratan.id) AS total_dokumen'
        )
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->join('dokumen_persyaratan', 'dokumen_persyaratan.permohonan_id = permohonan_sku.id', 'left')
            ->whereIn('permohonan_sku.status', $statuses)
            ->groupBy('permohonan_sku.id')
            ->orderBy('permohonan_sku.created_at', $orderDir)
            ->findAll(50);
    }
}
