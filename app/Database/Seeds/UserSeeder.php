<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'nik'           => '3404010101900001',
                'nip'           => '198504122010011008',
                'nama_lengkap'  => 'Administrator Kalurahan',
                'email'         => 'admin@kalurahan.go.id',
                'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
                'no_hp'         => '081234567890',
                'alamat'        => 'Kantor Kalurahan Condongcatur',
                'rt'            => '01',
                'rw'            => '01',
                'role'          => 'admin',
                'status_aktif'  => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nik'           => '3404010202920002',
                'nip'           => '199202022018011002',
                'nama_lengkap'  => 'Budi Santoso, S.Sos',
                'email'         => 'staf@kalurahan.go.id',
                'password_hash' => password_hash('staf123', PASSWORD_BCRYPT),
                'no_hp'         => '081234567891',
                'alamat'        => 'Jl. Ring Road Utara, Condongcatur',
                'rt'            => '02',
                'rw'            => '04',
                'role'          => 'staf',
                'status_aktif'  => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nik'           => '3404010303800003',
                'nip'           => '197003151995031002',
                'nama_lengkap'  => 'Drs. H. Reno Candra, M.Si',
                'email'         => 'lurah@kalurahan.go.id',
                'password_hash' => password_hash('lurah123', PASSWORD_BCRYPT),
                'no_hp'         => '081234567892',
                'alamat'        => 'Dero, Condongcatur, Depok, Sleman',
                'rt'            => '03',
                'rw'            => '08',
                'role'          => 'lurah',
                'status_aktif'  => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'nik'           => '3404011508950004',
                'nama_lengkap'  => 'Ahmad Kurniawan',
                'email'         => 'pemohon@gmail.com',
                'password_hash' => password_hash('pemohon123', PASSWORD_BCRYPT),
                'no_hp'         => '085712345678',
                'alamat'        => 'Pringwulung, Condongcatur, Depok, Sleman',
                'rt'            => '05',
                'rw'            => '02',
                'role'          => 'pemohon',
                'status_aktif'  => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        // Insert batch ignore if exists
        $this->db->table('users')->insertBatch($users);
    }
}
