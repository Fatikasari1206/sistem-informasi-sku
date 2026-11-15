<?php

namespace App\Controllers;

use App\Models\LogLoginModel;
use App\Models\MasterPendudukModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;
    protected MasterPendudukModel $pendudukModel;

    public function __construct()
    {
        $this->userModel     = new UserModel();
        $this->pendudukModel = new MasterPendudukModel();
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
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
            ],
            'password' => [
                'required' => 'Kata sandi wajib diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = strtolower(trim((string) $this->request->getPost('email')));
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

        $this->startUserSession($user);

        return $this->redirectToDashboard($user['role'])->with('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
    }

    // Halaman Login Internal Aparatur (Staf, Lurah, Admin)
    public function adminLogin()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard((string) session()->get('role'));
        }

        return view('auth/admin_login', [
            'title' => 'Portal Internal Aparatur - Sistem SKU',
        ]);
    }

    // Proses Autentikasi Internal Aparatur via NIP
    public function processAdminLogin()
    {
        $rules = [
            'nip'      => 'required',
            'password' => 'required',
        ];

        $messages = [
            'nip'      => ['required' => 'NIP wajib diisi.'],
            'password' => ['required' => 'Kata sandi wajib diisi.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nipInput = preg_replace('/\D/', '', (string) $this->request->getPost('nip'));
        $password = (string) $this->request->getPost('password');

        if (strlen($nipInput) < 8) {
            return redirect()->back()->withInput()->with('error', 'Format NIP tidak valid.');
        }

        $user = $this->userModel->findByNip($nipInput);

        if (!$user || !in_array($user['role'], ['admin', 'staf', 'lurah'], true)) {
            return redirect()->back()->withInput()->with('error', 'NIP tidak terdaftar dalam sistem.');
        }

        if ((int) $user['status_aktif'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi administrator.');
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Kata sandi salah.');
        }

        $this->startUserSession($user);

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

    // API Lookup Sinkronisasi NIK Master Penduduk
    public function cekNik()
    {
        $nik = trim((string) $this->request->getGet('nik'));

        if (!preg_match('/^\d{16}$/', $nik)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Format NIK harus berupa 16 digit angka.',
            ]);
        }

        $penduduk = $this->pendudukModel->where('nik', $nik)->first();

        if (!$penduduk) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'NIK tidak ditemukan dalam data kependudukan Kalurahan Condongcatur.',
            ]);
        }

        if ($this->userModel->findByNik($nik)) {
            return $this->response->setStatusCode(409)->setJSON([
                'success'            => false,
                'already_registered' => true,
                'message'            => 'NIK ini sudah terdaftar sebagai akun. Silakan langsung login.',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data kependudukan ditemukan dan tersinkronisasi.',
            'data'    => [
                'nik'               => $penduduk['nik'],
                'nama_lengkap'      => $penduduk['nama_lengkap'],
                'wilayah_padukuhan' => $this->pendudukModel->formatWilayah($penduduk),
            ],
        ]);
    }

    // Proses Simpan Registrasi Pemohon
    public function processRegister()
    {
        $input = [
            'nik'              => trim((string) $this->request->getPost('nik')),
            'email'            => strtolower(trim((string) $this->request->getPost('email'))),
            'no_hp'            => preg_replace('/[\s-]+/', '', (string) $this->request->getPost('no_hp')),
            'password'         => (string) $this->request->getPost('password'),
            'password_confirm' => (string) $this->request->getPost('password_confirm'),
            'persetujuan'      => $this->request->getPost('persetujuan'),
        ];

        $rules = [
            'nik'              => 'required|numeric|exact_length[16]|is_unique[users.nik]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'no_hp'            => 'required|numeric|min_length[10]|max_length[15]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'persetujuan'      => 'required',
        ];

        $messages = [
            'nik' => [
                'required'     => 'NIK KTP wajib diisi.',
                'numeric'      => 'NIK harus berupa angka.',
                'exact_length' => 'NIK harus berjumlah 16 digit.',
                'is_unique'    => 'NIK ini sudah terdaftar dalam sistem.',
            ],
            'email' => [
                'required'    => 'Alamat email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email ini sudah terdaftar, gunakan email lain atau silakan login.',
            ],
            'no_hp' => [
                'required'   => 'Nomor WhatsApp wajib diisi.',
                'numeric'    => 'Nomor WhatsApp harus berupa angka.',
                'min_length' => 'Nomor WhatsApp minimal 10 digit.',
                'max_length' => 'Nomor WhatsApp maksimal 15 digit.',
            ],
            'password' => [
                'required'   => 'Kata sandi wajib diisi.',
                'min_length' => 'Kata sandi minimal 6 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi kata sandi wajib diisi.',
                'matches'  => 'Konfirmasi kata sandi tidak cocok.',
            ],
            'persetujuan' => [
                'required' => 'Anda harus mencentang pernyataan kebenaran data kependudukan.',
            ],
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules, $messages);

        if (!$validation->run($input)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $penduduk = $this->pendudukModel->where('nik', $input['nik'])->first();
        if (!$penduduk) {
            return redirect()->back()->withInput()->with('error', 'NIK tidak ditemukan di data master kependudukan Kalurahan Condongcatur.');
        }

        $this->userModel->insert([
            'nik'           => $penduduk['nik'],
            'nama_lengkap'  => $penduduk['nama_lengkap'],
            'email'         => $input['email'],
            'password_hash' => password_hash($input['password'], PASSWORD_BCRYPT),
            'no_hp'         => $input['no_hp'],
            'alamat'        => $penduduk['alamat'] ?? ('Padukuhan ' . $penduduk['padukuhan'] . ', Condongcatur'),
            'rt'            => $penduduk['rt'],
            'rw'            => $penduduk['rw'],
            'role'          => 'pemohon',
            'status_aktif'  => 1,
        ]);

        return redirect()->to('/login')->with('success', 'Pendaftaran akun berhasil! Silakan masuk dengan email dan kata sandi Anda.');
    }

    // Logout
    public function logout()
    {
        $role = (string) session()->get('role');
        session()->destroy();

        $redirectTarget = in_array($role, ['admin', 'staf', 'lurah'], true) ? '/admin' : '/login';
        return redirect()->to($redirectTarget)->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    // Set Session Data Pengguna
    private function startUserSession(array $user): void
    {
        session()->set([
            'userId'       => $user['id'],
            'nik'          => $user['nik'],
            'nip'          => $user['nip'] ?? null,
            'namaLengkap'  => $user['nama_lengkap'],
            'email'        => $user['email'],
            'noHp'         => $user['no_hp'],
            'alamat'       => $user['alamat'],
            'rt'           => $user['rt'],
            'rw'           => $user['rw'],
            'role'         => $user['role'],
            'fotoProfil'   => $user['foto_profil'],
            'isLoggedIn'   => true,
            'loginLogged'  => true,
        ]);

        try {
            (new LogLoginModel())->recordLogin($user, (string) $this->request->getIPAddress());
        } catch (\Throwable $e) {
            log_message('error', 'Gagal mencatat log login: ' . $e->getMessage());
        }
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
