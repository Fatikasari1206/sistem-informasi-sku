<?php

namespace App\Controllers\Lurah;

use App\Controllers\BaseController;
use App\Models\PermohonanSkuModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $skuModel = new PermohonanSkuModel();

        $stats = [
            'menunggu_approval' => $skuModel->countByStatus('menunggu_persetujuan_lurah'),
            'disetujui'         => $skuModel->countByStatus('disetujui'),
            'selesai'           => $skuModel->countByStatus('selesai'),
            'total_terbit'      => $skuModel->where('nomor_surat IS NOT NULL')->countAllResults(),
        ];

        // Daftar permohonan yang menunggu tanda tangan / persetujuan Lurah
        $menunggu = $skuModel->where('status', 'menunggu_persetujuan_lurah')
                             ->orderBy('diverifikasi_pada', 'ASC')
                             ->findAll(10);

        return view('lurah/dashboard', [
            'title'    => 'Dashboard Persetujuan Lurah',
            'stats'    => $stats,
            'menunggu' => $menunggu,
            'user'     => session()->get(),
        ]);
    }
}
