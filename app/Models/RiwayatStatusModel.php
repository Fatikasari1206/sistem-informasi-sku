<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatStatusModel extends Model
{
    protected $table            = 'riwayat_status';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'permohonan_id',
        'status_sebelumnya',
        'status_baru',
        'user_id',
        'keterangan',
        'created_at',
    ];

    protected $useTimestamps = false;

    // Catat log perubahan status
    public function catat(int $permohonanId, ?string $statusSebelumnya, string $statusBaru, ?int $userId = null, ?string $keterangan = null): bool
    {
        return (bool) $this->insert([
            'permohonan_id'     => $permohonanId,
            'status_sebelumnya' => $statusSebelumnya,
            'status_baru'       => $statusBaru,
            'user_id'           => $userId,
            'keterangan'        => $keterangan,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);
    }

    public function getTimeline(int $permohonanId): array
    {
        return $this->select('riwayat_status.*, users.nama_lengkap as nama_user, users.role as role_user')
                    ->join('users', 'users.id = riwayat_status.user_id', 'left')
                    ->where('permohonan_id', $permohonanId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }
}
