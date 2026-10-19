<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
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
                'null'       => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'unique'     => true,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'rt' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'rw' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'pemohon', 'staf', 'lurah'],
                'default'    => 'pemohon',
            ],
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status_aktif' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('role');
        $this->forge->addKey('nik');
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
