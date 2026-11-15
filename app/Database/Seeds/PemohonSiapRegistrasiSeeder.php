<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PemohonSiapRegistrasiSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $rows = [
            [
                'nik'          => '3404070101990001',
                'nama_lengkap' => 'Dina Kusuma Wardani',
                'padukuhan'    => 'Manukan',
                'rt'           => '01',
                'rw'           => '03',
                'alamat'       => 'Padukuhan Manukan, Condongcatur, Depok, Sleman',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nik'          => '3404070202980002',
                'nama_lengkap' => 'Fajar Ramadhan',
                'padukuhan'    => 'Joho',
                'rt'           => '04',
                'rw'           => '06',
                'alamat'       => 'Padukuhan Joho, Condongcatur, Depok, Sleman',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        $this->db->table('master_penduduk')->ignore(true)->insertBatch($rows);
    }
}
