<?php

namespace App\Controllers\Staf;

use App\Controllers\BaseController;
use App\Models\PermohonanSkuModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $skuModel = new PermohonanSkuModel();

        $stats = [
            'total_masuk'    => $skuModel->whereIn('status', ['diajukan', 'verifikasi_staf'])->countAllResults(),
            'perlu_revisi'   => $skuModel->countByStatus('revisi'),
            'diteruskan'     => $skuModel->countByStatus('menunggu_persetujuan_lurah'),
            'selesai'        => $skuModel->countByStatus('selesai'),
        ];

        // 5 antrean permohonan terbaru yang butuh verifikasi
        $antrean = $skuModel->whereIn('status', ['diajukan', 'verifikasi_staf', 'revisi'])
                            ->orderBy('created_at', 'ASC')
                            ->findAll(5);

        return view('staf/dashboard', [
            'title'   => 'Dashboard Pelayanan Staf Kalurahan',
            'stats'   => $stats,
            'antrean' => $antrean,
            'user'    => session()->get(),
        ]);
    }
}
