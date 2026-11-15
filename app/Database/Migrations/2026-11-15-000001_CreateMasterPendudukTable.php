<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterPendudukTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'unique'     => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'padukuhan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'rt' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
            ],
            'rw' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('master_penduduk', true);
    }

    public function down()
    {
        $this->forge->dropTable('master_penduduk', true);
    }
}
