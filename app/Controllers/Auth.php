<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    // Halaman Form Login
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard(session()->get('role'));
        }

        return view('auth/login', [
            'title' => 'Masuk - Sistem Informasi SKU',
        ]);
    }

    // Proses Autentikasi Login
    public function processLogin()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        $messages = [
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'required' => 'Kata sandi wajib diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email tidak terdaftar dalam sistem.');
        }

        if ((int) $user['status_aktif'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi admin kalurahan.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Kata sandi salah.');
        }

        // Set Session Data
        $sessionData = [
            'userId'       => $user['id'],
            'nik'          => $user['nik'],
            'namaLengkap'  => $user['nama_lengkap'],
            'email'        => $user['email'],
            'noHp'         => $user['no_hp'],
            'alamat'       => $user['alamat'],
            'rt'           => $user['rt'],
            'rw'           => $user['rw'],
            'role'         => $user['role'],
            'fotoProfil'   => $user['foto_profil'],
            'isLoggedIn'   => true,
        ];
        session()->set($sessionData);

        return $this->redirectToDashboard($user['role'])->with('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
    }

    // Halaman Form Registrasi Warga / Pemohon
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard(session()->get('role'));
        }

        return view('auth/register', [
            'title' => 'Pendaftaran Pemohon - Sistem Informasi SKU',
        ]);
    }

    // Proses Simpan Registrasi Pemohon
    public function processRegister()
    {
        $rules = [
            'nik'              => 'required|numeric|exact_length[16]|is_unique[users.nik]',
            'nama_lengkap'     => 'required|min_length[3]|max_length[150]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'no_hp'            => 'required|numeric|min_length[10]|max_length[15]',
            'alamat'           => 'required',
            'rt'               => 'permit_empty|max_length[5]',
            'rw'               => 'permit_empty|max_length[5]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'nik' => [
                'required'     => 'NIK KTP wajib diisi.',
                'numeric'      => 'NIK harus berupa angka.',
                'exact_length' => 'NIK harus berjumlah 16 digit.',
                'is_unique'    => 'NIK ini sudah terdaftar dalam sistem.',
            ],
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi sesuai KTP.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email ini sudah terdaftar, gunakan email lain atau silakan login.',
            ],
            'password' => [
                'required'   => 'Kata sandi wajib diisi.',
                'min_length' => 'Kata sandi minimal 6 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi kata sandi wajib diisi.',
                'matches'  => 'Konfirmasi kata sandi tidak cocok.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nik'           => $this->request->getPost('nik'),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'email'         => strtolower(trim($this->request->getPost('email'))),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_BCRYPT),
            'no_hp'         => $this->request->getPost('no_hp'),
            'alamat'        => $this->request->getPost('alamat'),
            'rt'            => $this->request->getPost('rt'),
            'rw'            => $this->request->getPost('rw'),
            'role'          => 'pemohon',
            'status_aktif'  => 1,
        ];

        $this->userModel->insert($data);

        return redirect()->to('/login')->with('success', 'Pendaftaran akun berhasil! Silakan masuk dengan email dan kata sandi Anda.');
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    // Helper Redirect Dashboard berdasarkan Role
    private function redirectToDashboard(string $role)
    {
        $map = [
            'admin'   => '/admin/dashboard',
            'staf'    => '/staf/dashboard',
            'lurah'   => '/lurah/dashboard',
            'pemohon' => '/pemohon/dashboard',
        ];

        $url = $map[$role] ?? '/login';
        return redirect()->to($url);
    }
}
