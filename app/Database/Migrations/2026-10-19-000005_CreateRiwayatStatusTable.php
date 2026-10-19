<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiwayatStatusTable extends Migration
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
            'permohonan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status_sebelumnya' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'status_baru' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('permohonan_id');
        $this->forge->addForeignKey('permohonan_id', 'permohonan_sku', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('riwayat_status', true);
    }

    public function down()
    {
        $this->forge->dropTable('riwayat_status', true);
    }
}
