<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDokumenPersyaratanTable extends Migration
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
            'jenis_dokumen' => [
                'type'       => 'ENUM',
                'constraint' => ['ktp', 'kk', 'pengantar_rt_rw', 'foto_usaha', 'sku_lama', 'lainnya'],
            ],
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'ukuran_file' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'tipe_file' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'status_verifikasi' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'valid', 'tidak_valid'],
                'default'    => 'pending',
            ],
            'catatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('permohonan_id');
        $this->forge->addForeignKey('permohonan_id', 'permohonan_sku', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('dokumen_persyaratan', true);
    }

    public function down()
    {
        $this->forge->dropTable('dokumen_persyaratan', true);
    }
}
