<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table            = 'pengaturan_kalurahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_kalurahan',
        'nama_kapanewon',
        'kabupaten_kota',
        'provinsi',
        'alamat_kantor',
        'kode_pos',
        'telepon',
        'email',
        'website',
        'logo_instansi',
        'nama_lurah',
        'nip_lurah',
        'ttd_digital',
        'format_nomor_surat',
        'updated_at',
    ];

    public function getPengaturan()
    {
        $data = $this->first();
        if (!$data) {
            $default = [
                'nama_kalurahan'     => 'Condongcatur',
                'nama_kapanewon'     => 'Depok',
                'kabupaten_kota'     => 'Kabupaten Sleman',
                'provinsi'           => 'D.I. Yogyakarta',
                'format_nomor_surat' => '503/{nomor}/SKU/{bulan_romawi}/{tahun}',
                'updated_at'         => date('Y-m-d H:i:s'),
            ];
            $id = $this->insert($default);
            return $this->find($id);
        }
        return $data;
    }
}
