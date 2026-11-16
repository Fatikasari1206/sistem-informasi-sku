<?php

namespace App\Controllers\Lurah;

use App\Controllers\BaseController;
use App\Models\PengaturanModel;
use App\Models\PermohonanSkuModel;
use App\Models\RiwayatStatusModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $skuModel = new PermohonanSkuModel();

        $padukuhanDistribusi = $skuModel->select(
            "COALESCE(NULLIF(permohonan_sku.padukuhan_usaha, ''), master_penduduk.padukuhan, 'Condongcatur') AS padukuhan, COUNT(permohonan_sku.id) AS total",
            false
        )
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->groupBy("COALESCE(NULLIF(permohonan_sku.padukuhan_usaha, ''), master_penduduk.padukuhan, 'Condongcatur')", false)
            ->orderBy('total', 'DESC')
            ->findAll(6);

        $topPadukuhan = !empty($padukuhanDistribusi)
            ? $padukuhanDistribusi[0]['padukuhan'] . ' (' . (int) $padukuhanDistribusi[0]['total'] . ')'
            : '-';

        $stats = [
            'total_sku'     => $skuModel->countAllResults(),
            'menunggu_tte'  => $skuModel->countByStatus('menunggu_persetujuan_lurah'),
            'top_padukuhan' => $topPadukuhan,
        ];

        $chartRecords = $skuModel->select(
            "DATE(permohonan_sku.created_at) AS tanggal, COALESCE(NULLIF(permohonan_sku.padukuhan_usaha, ''), master_penduduk.padukuhan, '-') AS padukuhan",
            false
        )
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->orderBy('permohonan_sku.created_at', 'ASC')
            ->findAll();

        return view('lurah/dashboard', [
            'title'         => 'Dashboard Lurah - Sistem SKU',
            'logoutLabel'   => 'Keluar Sistem',
            'stats'         => $stats,
            'chartRecords'  => $chartRecords,
            'padukuhanList' => self::PADUKUHAN_LIST,
        ]);
    }

    public function antrian()
    {
        $antrean = $this->fetchPermohonanByStatus(['menunggu_persetujuan_lurah'], 'diverifikasi_pada', 'ASC');

        return view('lurah/antrian', [
            'title'         => 'Antrian Masuk Lurah - Sistem SKU',
            'logoutLabel'   => 'Keluar Sistem',
            'antrean'       => $antrean,
            'padukuhanList' => self::PADUKUHAN_LIST,
        ]);
    }

    public function pengesahan()
    {
        $skuModel = new PermohonanSkuModel();
        $first    = $skuModel->where('status', 'menunggu_persetujuan_lurah')
            ->orderBy('diverifikasi_pada', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->first();

        if ($first) {
            return redirect()->to('/lurah/tte/' . (int) $first['id']);
        }

        return redirect()->to('/lurah/antrian');
    }

    public function arsip()
    {
        $daftarSku = $this->fetchPermohonanByStatus(['disetujui', 'selesai'], 'disetujui_pada', 'DESC');

        return view('lurah/arsip', [
            'title'         => 'Arsip SKU Terbit - Sistem SKU',
            'logoutLabel'   => 'Keluar Sistem',
            'daftarSku'     => $daftarSku,
            'padukuhanList' => self::PADUKUHAN_LIST,
        ]);
    }

    public function tte(int $id)
    {
        $skuModel        = new PermohonanSkuModel();
        $pengaturanModel = new PengaturanModel();

        $permohonan = $skuModel->select(
            'permohonan_sku.*, '
            . 'COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan, '
            . 'master_penduduk.padukuhan AS domisili_padukuhan, '
            . 'master_penduduk.rt AS domisili_rt, '
            . 'master_penduduk.rw AS domisili_rw, '
            . 'master_penduduk.alamat AS domisili_alamat'
        )
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->find($id);

        if (!$permohonan) {
            return redirect()->to('/lurah/dashboard')->with('error', 'Data permohonan SKU tidak ditemukan.');
        }

        $antreanTte = $this->fetchPermohonanByStatus(['menunggu_persetujuan_lurah'], 'diverifikasi_pada', 'ASC', 30);
        $pengaturan = $pengaturanModel->getPengaturan();

        return view('lurah/tte', [
            'title'      => 'Portal Pengesahan SKU (TTE Digital) - Sistem SKU',
            'permohonan' => $permohonan,
            'antreanTte' => $antreanTte,
            'pengaturan' => $pengaturan,
        ]);
    }

    public function prosesTte(int $id)
    {
        $skuModel     = new PermohonanSkuModel();
        $riwayatModel = new RiwayatStatusModel();

        $permohonan = $skuModel->find($id);
        if (!$permohonan) {
            return redirect()->to('/lurah/dashboard')->with('error', 'Data permohonan SKU tidak ditemukan.');
        }

        if ($permohonan['status'] !== 'menunggu_persetujuan_lurah') {
            return redirect()->to('/lurah/dashboard')->with('error', 'Permohonan ini tidak berada dalam antrian pengesahan Lurah.');
        }

        $aksi       = (string) $this->request->getPost('aksi');
        $catatan    = trim((string) $this->request->getPost('catatan'));
        $userId     = (int) session()->get('userId');
        $now        = date('Y-m-d H:i:s');
        $statusLama = (string) $permohonan['status'];

        if ($aksi === 'sahkan') {
            $tanggalSurat  = !empty($permohonan['tanggal_surat']) ? $permohonan['tanggal_surat'] : date('Y-m-d');
            $berlakuSampai = !empty($permohonan['berlaku_sampai'])
                ? $permohonan['berlaku_sampai']
                : date('Y-m-d', strtotime($tanggalSurat . ' +1 year'));
            $qrToken = !empty($permohonan['qr_token'])
                ? $permohonan['qr_token']
                : 'BSrE-' . strtoupper(substr(hash('sha256', $permohonan['kode_pengajuan'] . '|' . $now . '|' . random_bytes(8)), 0, 12));

            $skuModel->update($id, [
                'status'         => 'disetujui',
                'tanggal_surat'  => $tanggalSurat,
                'berlaku_sampai' => $berlakuSampai,
                'lurah_id'       => $userId,
                'disetujui_pada' => $now,
                'qr_token'       => $qrToken,
                'catatan_revisi' => null,
            ]);

            $riwayatModel->catat(
                $id,
                $statusLama,
                'disetujui',
                $userId,
                'Surat Keterangan Usaha disahkan secara elektronik (TTE) oleh Lurah.'
            );

            $nomorText = !empty($permohonan['nomor_surat']) ? " ({$permohonan['nomor_surat']})" : '';
            return redirect()->to('/lurah/dashboard')
                ->with('success', "SKU {$permohonan['kode_pengajuan']}{$nomorText} berhasil disahkan dengan TTE Digital.");
        }

        if ($aksi === 'kembalikan') {
            if ($catatan === '') {
                return redirect()->back()->withInput()
                    ->with('error', 'Tuliskan alasan pengembalian agar Staf Pelayanan mengetahui hal yang perlu diperiksa ulang.');
            }

            $skuModel->update($id, [
                'status'         => 'verifikasi_staf',
                'catatan_revisi' => $catatan,
            ]);

            $riwayatModel->catat(
                $id,
                $statusLama,
                'verifikasi_staf',
                $userId,
                'Dikembalikan oleh Lurah ke Staf: ' . $catatan
            );

            return redirect()->to('/lurah/dashboard')
                ->with('success', "Permohonan {$permohonan['kode_pengajuan']} dikembalikan ke Staf Pelayanan.");
        }

        return redirect()->back()->with('error', 'Tindakan pengesahan tidak dikenali.');
    }

    private function fetchPermohonanByStatus(array $statuses, string $orderCol, string $orderDir, int $limit = 50): array
    {
        $skuModel = new PermohonanSkuModel();

        return $skuModel->select(
            'permohonan_sku.*, '
            . 'COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan'
        )
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->whereIn('permohonan_sku.status', $statuses)
            ->orderBy("permohonan_sku.{$orderCol}", $orderDir)
            ->orderBy('permohonan_sku.created_at', $orderDir)
            ->findAll($limit);
    }
}
