<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermohonanSkuTable extends Migration
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
            'kode_pengajuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tipe_pengajuan' => [
                'type'       => 'ENUM',
                'constraint' => ['baru', 'perpanjangan'],
                'default'    => 'baru',
            ],
            'permohonan_sebelumnya_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            // Data Pemohon
            'nama_pemohon' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'nik_pemohon' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'default'    => 'L',
            ],
            'pekerjaan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'agama' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'alamat_pemohon' => [
                'type' => 'TEXT',
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            // Data Usaha
            'nama_usaha' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'bidang_usaha' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'alamat_usaha' => [
                'type' => 'TEXT',
            ],
            'rt_usaha' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'rw_usaha' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
            ],
            'lama_usaha_tahun' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 1,
            ],
            'modal_usaha' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'null'       => true,
            ],
            'omset_per_bulan' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'null'       => true,
            ],
            'jumlah_karyawan' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'status_tempat_usaha' => [
                'type'       => 'ENUM',
                'constraint' => ['milik_sendiri', 'sewa', 'menumpang', 'lainnya'],
                'default'    => 'milik_sendiri',
            ],
            'deskripsi_usaha' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Surat & Workflow Status
            'nomor_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'tanggal_surat' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'berlaku_sampai' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'diajukan', 'verifikasi_staf', 'revisi', 'menunggu_persetujuan_lurah', 'disetujui', 'ditolak', 'selesai'],
                'default'    => 'diajukan',
            ],
            'catatan_revisi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'catatan_penolakan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Verifikasi & Approval
            'staf_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'diverifikasi_pada' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'lurah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'disetujui_pada' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            // QR Code Security
            'qr_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'       => true,
                'unique'     => true,
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->addKey('tipe_pengajuan');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('permohonan_sku', true);
    }

    public function down()
    {
        $this->forge->dropTable('permohonan_sku', true);
    }
}
