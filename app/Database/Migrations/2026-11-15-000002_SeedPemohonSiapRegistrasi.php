<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class SeedPemohonSiapRegistrasi extends Migration
{
    public function up()
    {
        Database::seeder()->call('PemohonSiapRegistrasiSeeder');
    }

    public function down()
    {
        $this->db->table('master_penduduk')
            ->whereIn('nik', ['3404070101990001', '3404070202980002'])
            ->delete();
    }
}
