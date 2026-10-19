<?php

namespace App\Controllers\Pemohon;

use App\Controllers\BaseController;
use App\Models\PermohonanSkuModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $skuModel = new PermohonanSkuModel();
        $userId   = session()->get('userId');

        $stats = [
            'total_pengajuan' => $skuModel->where('user_id', $userId)->countAllResults(),
            'sedang_proses'   => $skuModel->where('user_id', $userId)->whereIn('status', ['diajukan', 'verifikasi_staf', 'menunggu_persetujuan_lurah'])->countAllResults(),
            'perlu_revisi'    => $skuModel->where('user_id', $userId)->where('status', 'revisi')->countAllResults(),
            'selesai'         => $skuModel->where('user_id', $userId)->whereIn('status', ['disetujui', 'selesai'])->countAllResults(),
        ];

        // Daftar permohonan milik pemohon
        $permohonanList = $skuModel->where('user_id', $userId)
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll(10);

        return view('pemohon/dashboard', [
            'title'          => 'Dashboard Pemohon SKU',
            'stats'          => $stats,
            'permohonanList' => $permohonanList,
            'user'           => session()->get(),
        ]);
    }
}
