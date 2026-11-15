<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogLoginModel;
use App\Models\MasterPendudukModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel     = new UserModel();
        $pendudukModel = new MasterPendudukModel();
        $logLoginModel = new LogLoginModel();

        $stats = [
            'total_pemohon'  => $userModel->where('role', 'pemohon')->countAllResults(),
            'total_aparatur' => $userModel->whereIn('role', ['admin', 'staf', 'lurah'])->countAllResults(),
            'total_nik'      => $pendudukModel->countAllResults(),
        ];

        $loginChartSeries = $logLoginModel->getChartSeries();

        return view('admin/dashboard', [
            'title'            => 'Dashboard Administrator - Sistem SKU',
            'logoutLabel'      => 'Keluar Admin',
            'stats'            => $stats,
            'loginChartSeries' => $loginChartSeries,
        ]);
    }
}
