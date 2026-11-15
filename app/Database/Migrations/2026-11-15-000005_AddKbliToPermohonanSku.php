<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKbliToPermohonanSku extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('kbli', 'permohonan_sku')) {
            $this->forge->addColumn('permohonan_sku', [
                'kbli' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '150',
                    'null'       => true,
                    'after'      => 'bidang_usaha',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('kbli', 'permohonan_sku')) {
            $this->forge->dropColumn('permohonan_sku', 'kbli');
        }
    }
}
