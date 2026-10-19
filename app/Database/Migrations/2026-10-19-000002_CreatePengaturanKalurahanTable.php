<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengaturanKalurahanTable extends Migration
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
            'nama_kalurahan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Kalurahan Contoh',
            ],
            'nama_kapanewon' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Kecamatan / Kapanewon Contoh',
            ],
            'kabupaten_kota' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Kabupaten Contoh',
            ],
            'provinsi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'D.I. Yogyakarta',
            ],
            'alamat_kantor' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kode_pos' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'website' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'logo_instansi' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'nama_lurah' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'default'    => 'Nama Lurah, S.IP.',
            ],
            'nip_lurah' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'ttd_digital' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'format_nomor_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => '503/{nomor}/SKU/{bulan_romawi}/{tahun}',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('pengaturan_kalurahan', true);
    }

    public function down()
    {
        $this->forge->dropTable('pengaturan_kalurahan', true);
    }
}
