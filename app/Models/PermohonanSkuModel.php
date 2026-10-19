<?php

namespace App\Models;

use CodeIgniter\Model;

class PermohonanSkuModel extends Model
{
    protected $table            = 'permohonan_sku';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_pengajuan',
        'user_id',
        'tipe_pengajuan',
        'permohonan_sebelumnya_id',
        'nama_pemohon',
        'nik_pemohon',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'agama',
        'alamat_pemohon',
        'no_hp',
        'nama_usaha',
        'bidang_usaha',
        'alamat_usaha',
        'rt_usaha',
        'rw_usaha',
        'lama_usaha_tahun',
        'modal_usaha',
        'omset_per_bulan',
        'jumlah_karyawan',
        'status_tempat_usaha',
        'deskripsi_usaha',
        'nomor_surat',
        'tanggal_surat',
        'berlaku_sampai',
        'status',
        'catatan_revisi',
        'catatan_penolakan',
        'staf_id',
        'diverifikasi_pada',
        'lurah_id',
        'disetujui_pada',
        'qr_token',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Generator Kode Unik Pengajuan: SKU-YYYYMMDD-XXXX
    public function generateKodePengajuan(): string
    {
        $today = date('Ymd');
        $prefix = "SKU-{$today}-";
        $last = $this->like('kode_pengajuan', $prefix, 'after')
                     ->orderBy('id', 'DESC')
                     ->first();

        if ($last) {
            $lastNumber = (int) substr($last['kode_pengajuan'], -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix . $nextNumber;
    }

    // Generator Nomor Surat Resmi
    public function generateNomorSurat(string $format): string
    {
        $year = date('Y');
        $monthRoman = $this->getRomanMonth((int) date('n'));
        $countThisYear = $this->where('YEAR(created_at)', $year)
                              ->where('nomor_surat IS NOT NULL')
                              ->countAllResults() + 1;
        $padded = str_pad($countThisYear, 3, '0', STR_PAD_LEFT);

        return str_replace(
            ['{nomor}', '{bulan_romawi}', '{bulan}', '{tahun}'],
            [$padded, $monthRoman, date('m'), $year],
            $format
        );
    }

    private function getRomanMonth(int $month): string
    {
        $romans = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $romans[$month] ?? 'I';
    }

    // Hitung permohonan berdasarkan status
    public function countByStatus(string $status, ?int $userId = null): int
    {
        $builder = $this->where('status', $status);
        if ($userId !== null) {
            $builder->where('user_id', $userId);
        }
        return $builder->countAllResults();
    }
}
