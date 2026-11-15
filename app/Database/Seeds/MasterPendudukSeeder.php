<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterPendudukSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $rows = [
            [
                'nik'          => '3404071205980003',
                'nama_lengkap' => 'Cindy Alvionita',
                'padukuhan'    => 'Gejayan',
                'rt'           => '04',
                'rw'           => '08',
                'alamat'       => 'Padukuhan Gejayan, Condongcatur, Depok, Sleman',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nik'          => '3404072108940001',
                'nama_lengkap' => 'Rizky Pratama Putra',
                'padukuhan'    => 'Kentungan',
                'rt'           => '02',
                'rw'           => '05',
                'alamat'       => 'Padukuhan Kentungan, Condongcatur, Depok, Sleman',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nik'          => '3404074503970002',
                'nama_lengkap' => 'Nadia Putri Maharani',
                'padukuhan'    => 'Sanggrahan',
                'rt'           => '06',
                'rw'           => '11',
                'alamat'       => 'Padukuhan Sanggrahan, Condongcatur, Depok, Sleman',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nik'          => '3404071011900005',
                'nama_lengkap' => 'Bagas Adi Nugroho',
                'padukuhan'    => 'Soropadan',
                'rt'           => '03',
                'rw'           => '07',
                'alamat'       => 'Padukuhan Soropadan, Condongcatur, Depok, Sleman',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nik'          => '3404011508950004',
                'nama_lengkap' => 'Ahmad Kurniawan',
                'padukuhan'    => 'Pringwulung',
                'rt'           => '05',
                'rw'           => '02',
                'alamat'       => 'Pringwulung, Condongcatur, Depok, Sleman',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        $this->db->table('master_penduduk')->ignore(true)->insertBatch($rows);
    }
}
