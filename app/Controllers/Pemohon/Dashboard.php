<?php

namespace App\Controllers\Pemohon;

use App\Controllers\BaseController;
use App\Models\PermohonanSkuModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $skuModel = new PermohonanSkuModel();
        $userId   = (int) session()->get('userId');

        $nearestExpiryRow = $skuModel->select('berlaku_sampai')
            ->where('user_id', $userId)
            ->whereIn('status', ['disetujui', 'selesai'])
            ->where('berlaku_sampai IS NOT NULL', null, false)
            ->orderBy('berlaku_sampai', 'ASC')
            ->first();

        $stats = [
            'total_sku_aktif'       => $skuModel->where('user_id', $userId)->whereIn('status', ['disetujui', 'selesai'])->countAllResults(),
            'dalam_verifikasi'      => $skuModel->where('user_id', $userId)->whereIn('status', ['diajukan', 'verifikasi_staf'])->countAllResults(),
            'menunggu_lurah'        => $skuModel->where('user_id', $userId)->where('status', 'menunggu_persetujuan_lurah')->countAllResults(),
            'masa_berlaku_terdekat' => $nearestExpiryRow['berlaku_sampai'] ?? null,
        ];

        $permohonanList = $skuModel->select('permohonan_sku.*, COALESCE(permohonan_sku.padukuhan_usaha, master_penduduk.padukuhan) AS padukuhan')
            ->join('master_penduduk', 'master_penduduk.nik = permohonan_sku.nik_pemohon', 'left')
            ->where('permohonan_sku.user_id', $userId)
            ->orderBy('permohonan_sku.created_at', 'DESC')
            ->findAll();

        return view('pemohon/dashboard', [
            'title'          => 'Dashboard Warga Pemohon - Sistem SKU',
            'stats'          => $stats,
            'permohonanList' => $permohonanList,
            'user'           => session()->get(),
        ]);
    }
}
