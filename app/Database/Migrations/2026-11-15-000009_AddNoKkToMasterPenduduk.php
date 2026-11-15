<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNoKkToMasterPenduduk extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('no_kk', 'master_penduduk')) {
            $this->forge->addColumn('master_penduduk', [
                'no_kk' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '16',
                    'null'       => true,
                    'after'      => 'nama_lengkap',
                ],
            ]);
        }

        // Isi default contoh No. KK untuk data seeder yang sudah ada
        $kkDefaults = [
            '3404071205980003' => '3404071501200005',
            '3404072108940001' => '3404071501200004',
            '3404074503970002' => '3404071501200002',
            '3404071011900005' => '3404071201990008',
            '3404011508950004' => '3404011201930011',
        ];

        foreach ($kkDefaults as $nik => $noKk) {
            $this->db->table('master_penduduk')
                ->where('nik', $nik)
                ->where('no_kk IS NULL', null, false)
                ->update(['no_kk' => $noKk]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('no_kk', 'master_penduduk')) {
            $this->forge->dropColumn('master_penduduk', 'no_kk');
        }
    }
}
