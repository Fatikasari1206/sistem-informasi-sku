<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNipToUsersAndSeedInternalNip extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('nip', 'users')) {
            $this->forge->addColumn('users', [
                'nip' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '30',
                    'null'       => true,
                    'after'      => 'nik',
                ],
            ]);
            $this->db->query('CREATE INDEX idx_users_nip ON users (nip)');
        }

        $nipDefaults = [
            'admin@kalurahan.go.id' => '198504122010011008',
            'staf@kalurahan.go.id'  => '199202022018011002',
            'lurah@kalurahan.go.id' => '197003151995031002',
        ];

        foreach ($nipDefaults as $email => $nip) {
            $this->db->table('users')
                ->where('email', $email)
                ->where('nip IS NULL', null, false)
                ->update(['nip' => $nip]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('nip', 'users')) {
            $this->db->query('DROP INDEX idx_users_nip ON users');
            $this->forge->dropColumn('users', 'nip');
        }
    }
}
