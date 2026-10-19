<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use App\Models\PermohonanSkuModel;

class Home extends BaseController
{
    public function index(): string
    {
        $pengaturan = (new PengaturanModel())->getPengaturan();
        $skuModel   = new PermohonanSkuModel();

        $stats = [
            'total_terbit' => $skuModel->where('nomor_surat IS NOT NULL')->countAllResults(),
        ];

        return view('home', [
            'title'      => 'Layanan Surat Keterangan Usaha (SKU) - Kalurahan ' . ($pengaturan['nama_kalurahan'] ?? 'Condongcatur'),
            'pengaturan' => $pengaturan,
            'stats'      => $stats,
        ]);
    }
}
