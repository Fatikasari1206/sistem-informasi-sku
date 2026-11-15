<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPadukuhanUsahaToPermohonanSku extends Migration
{
    public function up()
    {
        $this->forge->addColumn('permohonan_sku', [
            'padukuhan_usaha' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'alamat_usaha',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('permohonan_sku', 'padukuhan_usaha');
    }
}
