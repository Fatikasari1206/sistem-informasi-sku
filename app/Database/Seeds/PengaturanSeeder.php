<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama_kalurahan'      => 'Condongcatur',
            'nama_kapanewon'      => 'Depok',
            'kabupaten_kota'      => 'Kabupaten Sleman',
            'provinsi'            => 'D.I. Yogyakarta',
            'alamat_kantor'       => 'Jl. Ring Road Utara No. 1, Condongcatur, Depok, Sleman',
            'kode_pos'            => '55283',
            'telepon'             => '(0274) 885689',
            'email'               => 'kalurahan@condongcatur.desa.id',
            'website'             => 'https://condongcatur.desa.id',
            'nama_lurah'          => 'Drs. H. Reno Candra, M.Si',
            'nip_lurah'           => '19700315 199503 1 002',
            'format_nomor_surat'  => '503/{nomor}/SKU/{bulan_romawi}/{tahun}',
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        $this->db->table('pengaturan_kalurahan')->insert($data);
    }
}
