<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanUserFullNames extends Migration
{
    public function up()
    {
        $cleanNames = [
            'staf@kalurahan.go.id'  => 'Budi Santoso, S.Sos',
            'lurah@kalurahan.go.id' => 'Drs. H. Reno Candra, M.Si',
            'pemohon@gmail.com'     => 'Ahmad Kurniawan',
        ];

        foreach ($cleanNames as $email => $nama) {
            $this->db->table('users')
                ->where('email', $email)
                ->update(['nama_lengkap' => $nama]);
        }
    }

    public function down()
    {
        // Tidak perlu mengembalikan akhiran role pada nama lengkap.
    }
}
