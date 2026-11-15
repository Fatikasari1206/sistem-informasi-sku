<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Aparatur extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $aparaturList = $this->userModel
            ->whereIn('role', ['lurah', 'staf', 'admin'])
            ->orderBy("FIELD(role, 'lurah', 'staf', 'admin')", '', false)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/aparatur', [
            'title'        => 'Kelola Akun Aparatur - Sistem SKU',
            'logoutLabel'  => 'Keluar Admin',
            'aparaturList' => $aparaturList,
        ]);
    }

    public function store()
    {
        $namaLengkap = trim((string) $this->request->getPost('nama_lengkap'));
        $nipRaw      = trim((string) $this->request->getPost('nip'));
        $nipClean    = preg_replace('/\D/', '', $nipRaw);
        $role        = trim((string) $this->request->getPost('role'));
        $jabatan     = trim((string) $this->request->getPost('jabatan'));
        $email       = strtolower(trim((string) $this->request->getPost('email')));
        $password    = (string) $this->request->getPost('password');

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'nip'          => 'required',
            'role'         => 'required|in_list[staf,lurah,admin]',
            'jabatan'      => 'required|min_length[3]|max_length[120]',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
        ];

        $messages = [
            'nama_lengkap' => [
                'required'   => 'Nama lengkap & gelar aparatur wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'nip' => [
                'required' => 'Nomor Induk Pegawai (NIP) wajib diisi.',
            ],
            'role' => [
                'required' => 'Role kedinasan wajib dipilih.',
                'in_list'  => 'Role kedinasan tidak valid.',
            ],
            'jabatan' => [
                'required'   => 'Jabatan kedinasan wajib diisi.',
                'min_length' => 'Jabatan kedinasan minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Email kedinasan wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email kedinasan sudah terdaftar pada akun lain.',
            ],
            'password' => [
                'required'   => 'Password awal wajib diisi.',
                'min_length' => 'Password awal minimal 6 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (strlen($nipClean) < 8 || strlen($nipClean) > 30) {
            return redirect()->back()->withInput()->with('errors', [
                'nip' => 'NIP harus terdiri dari 8 hingga 30 digit angka.',
            ]);
        }

        if ($this->userModel->findByNip($nipClean)) {
            return redirect()->back()->withInput()->with('errors', [
                'nip' => 'NIP ini sudah terdaftar pada akun aparatur lain.',
            ]);
        }

        $this->userModel->insert([
            'nik'           => null,
            'nip'           => $nipClean,
            'nama_lengkap'  => $namaLengkap,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role'          => $role,
            'jabatan'       => $jabatan,
            'status_aktif'  => 1,
        ]);

        return redirect()->to('/admin/aparatur')
            ->with('success', 'Akun aparatur atas nama ' . $namaLengkap . ' berhasil diterbitkan.');
    }

    public function update(int $id)
    {
        $user = $this->userModel
            ->where('id', $id)
            ->whereIn('role', ['lurah', 'staf', 'admin'])
            ->first();

        if (!$user) {
            return redirect()->to('/admin/aparatur')->with('error', 'Akun aparatur tidak ditemukan.');
        }

        $namaLengkap = trim((string) $this->request->getPost('nama_lengkap'));
        $nipRaw      = trim((string) $this->request->getPost('nip'));
        $nipClean    = preg_replace('/\D/', '', $nipRaw);
        $role        = trim((string) $this->request->getPost('role'));
        $jabatan     = trim((string) $this->request->getPost('jabatan'));
        $email       = strtolower(trim((string) $this->request->getPost('email')));
        $password    = (string) $this->request->getPost('password');

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'nip'          => 'required',
            'role'         => 'required|in_list[staf,lurah,admin]',
            'jabatan'      => 'required|min_length[3]|max_length[120]',
            'email'        => "required|valid_email|is_unique[users.email,id,{$id}]",
            'password'     => 'permit_empty|min_length[6]',
        ];

        $messages = [
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'nip' => [
                'required' => 'NIP wajib diisi.',
            ],
            'role' => [
                'required' => 'Hak akses wajib dipilih.',
                'in_list'  => 'Hak akses tidak valid.',
            ],
            'jabatan' => [
                'required'   => 'Jabatan wajib diisi.',
                'min_length' => 'Jabatan minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah digunakan oleh akun lain.',
            ],
            'password' => [
                'min_length' => 'Password minimal 6 karakter jika ingin diubah.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (strlen($nipClean) < 8 || strlen($nipClean) > 30) {
            return redirect()->back()->withInput()->with('errors', [
                'nip' => 'NIP harus terdiri dari 8 hingga 30 digit angka.',
            ]);
        }

        $existingNipUser = $this->userModel->findByNip($nipClean);
        if ($existingNipUser && (int) $existingNipUser['id'] !== $id) {
            return redirect()->back()->withInput()->with('errors', [
                'nip' => 'NIP ini sudah terdaftar pada akun aparatur lain.',
            ]);
        }

        if ((int) session()->get('userId') === $id && $role !== 'admin') {
            return redirect()->back()->withInput()->with('errors', [
                'role' => 'Akun admin yang sedang login tidak dapat diturunkan hak aksesnya.',
            ]);
        }

        $updateData = [
            'nip'          => $nipClean,
            'nama_lengkap' => $namaLengkap,
            'email'        => $email,
            'role'         => $role,
            'jabatan'      => $jabatan,
        ];

        if ($password !== '') {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userModel->update($id, $updateData);

        if ((int) session()->get('userId') === $id) {
            session()->set([
                'namaLengkap' => $namaLengkap,
                'email'       => $email,
                'nip'         => $nipClean,
            ]);
        }

        return redirect()->to('/admin/aparatur')
            ->with('success', 'Data akun aparatur ' . $namaLengkap . ' berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $user = $this->userModel
            ->where('id', $id)
            ->whereIn('role', ['lurah', 'staf', 'admin'])
            ->first();

        if (!$user) {
            return redirect()->to('/admin/aparatur')->with('error', 'Akun aparatur tidak ditemukan.');
        }

        if ((int) session()->get('userId') === $id) {
            return redirect()->to('/admin/aparatur')->with('error', 'Akun admin yang sedang login tidak dapat dihapus.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/admin/aparatur')
            ->with('success', 'Akun aparatur ' . $user['nama_lengkap'] . ' berhasil dihapus.');
    }

    public function toggleStatus(int $id)
    {
        $user = $this->userModel
            ->where('id', $id)
            ->whereIn('role', ['lurah', 'staf', 'admin'])
            ->first();

        if (!$user) {
            return redirect()->to('/admin/aparatur')->with('error', 'Akun aparatur tidak ditemukan.');
        }

        if ((int) session()->get('userId') === $id) {
            return redirect()->to('/admin/aparatur')->with('error', 'Akun admin yang sedang digunakan tidak dapat dinonaktifkan.');
        }

        $nextStatus = ((int) $user['status_aktif'] === 1) ? 0 : 1;
        $this->userModel->update($id, ['status_aktif' => $nextStatus]);

        $statusLabel = $nextStatus === 1 ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->to('/admin/aparatur')
            ->with('success', 'Akun aparatur ' . $user['nama_lengkap'] . ' berhasil ' . $statusLabel . '.');
    }
}
