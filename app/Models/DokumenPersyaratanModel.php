<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenPersyaratanModel extends Model
{
    protected $table            = 'dokumen_persyaratan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'permohonan_id',
        'jenis_dokumen',
        'nama_file',
        'file_path',
        'ukuran_file',
        'tipe_file',
        'status_verifikasi',
        'catatan',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function getByPermohonanId(int $permohonanId): array
    {
        return $this->where('permohonan_id', $permohonanId)->findAll();
    }
}
