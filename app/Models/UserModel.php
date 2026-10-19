<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nik',
        'nama_lengkap',
        'email',
        'password_hash',
        'no_hp',
        'alamat',
        'rt',
        'rw',
        'role',
        'foto_profil',
        'status_aktif',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Cari user berdasarkan email
    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    // Cari user berdasarkan NIK
    public function findByNik(string $nik)
    {
        return $this->where('nik', $nik)->first();
    }

    // Hitung total user per role untuk dashboard
    public function countByRole(string $role)
    {
        return $this->where('role', $role)->countAllResults();
    }
}
