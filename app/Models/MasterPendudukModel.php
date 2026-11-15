<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterPendudukModel extends Model
{
    protected $table            = 'master_penduduk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nik',
        'nama_lengkap',
        'no_kk',
        'padukuhan',
        'rt',
        'rw',
        'alamat',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function formatWilayah(array $penduduk): string
    {
        return sprintf('%s (RT %s / RW %s)', $penduduk['padukuhan'], $penduduk['rt'], $penduduk['rw']);
    }
}
