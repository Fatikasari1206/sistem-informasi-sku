<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJabatanToUsersTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('jabatan', 'users')) {
            $this->forge->addColumn('users', [
                'jabatan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '120',
                    'null'       => true,
                    'after'      => 'role',
                ],
            ]);
        }

        $defaults = [
            'admin' => 'Administrator Sistem',
            'staf'  => 'Staf Pelayanan Umum',
            'lurah' => 'Lurah Condongcatur',
        ];

        foreach ($defaults as $role => $jabatan) {
            $this->db->table('users')
                ->where('role', $role)
                ->groupStart()
                    ->where('jabatan IS NULL', null, false)
                    ->orWhere('jabatan', '')
                ->groupEnd()
                ->update(['jabatan' => $jabatan]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('jabatan', 'users')) {
            $this->forge->dropColumn('users', 'jabatan');
        }
    }
}
