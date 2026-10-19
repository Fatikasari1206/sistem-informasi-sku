<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PermohonanSkuModel;
use App\Models\PengaturanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $skuModel  = new PermohonanSkuModel();
        $pengaturan = (new PengaturanModel())->getPengaturan();

        $stats = [
            'total_users'     => $userModel->countAllResults(),
            'total_pemohon'   => $userModel->countByRole('pemohon'),
            'total_staf'      => $userModel->countByRole('staf'),
            'total_sku'       => $skuModel->countAllResults(),
            'sku_diajukan'    => $skuModel->countByStatus('diajukan'),
            'sku_verifikasi'  => $skuModel->countByStatus('verifikasi_staf'),
            'sku_menunggu'    => $skuModel->countByStatus('menunggu_persetujuan_lurah'),
            'sku_selesai'     => $skuModel->countByStatus('selesai'),
            'sku_ditolak'     => $skuModel->countByStatus('ditolak'),
        ];

        return view('admin/dashboard', [
            'title'      => 'Dashboard Administrator',
            'stats'      => $stats,
            'pengaturan' => $pengaturan,
            'user'       => session()->get(),
        ]);
    }
}
