<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            $firstSegment = $request->getUri()->getSegment(1);
            $loginPath    = in_array($firstSegment, ['admin', 'staf', 'lurah'], true) ? '/admin' : '/login';

            return redirect()->to($loginPath)->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = (int) $session->get('userId');
        $user   = $userId > 0 ? (new \App\Models\UserModel())->find($userId) : null;

        if (!$user || (int) ($user['status_aktif'] ?? 0) !== 1) {
            $firstSegment = $request->getUri()->getSegment(1);
            $loginPath    = in_array($firstSegment, ['admin', 'staf', 'lurah'], true) ? '/admin' : '/login';
            $session->destroy();

            return redirect()->to($loginPath)->with('error', 'Sesi berakhir atau akun Anda telah dinonaktifkan.');
        }

        $userRole = (string) $user['role'];
        if ($session->get('role') !== $userRole || $session->get('namaLengkap') !== $user['nama_lengkap']) {
            $session->set([
                'role'        => $userRole,
                'namaLengkap' => $user['nama_lengkap'],
                'nik'         => $user['nik'],
                'nip'         => $user['nip'] ?? null,
            ]);
        }

        // Pastikan sesi yang sudah login sebelum fitur log_login dibuat tetap tercatat 1x
        if (!$session->get('loginLogged')) {
            try {
                (new \App\Models\LogLoginModel())->recordLogin($user, (string) $request->getIPAddress());
                $session->set('loginLogged', true);
            } catch (\Throwable $e) {
                log_message('error', 'Gagal mencatat sesi aktif ke log_login: ' . $e->getMessage());
            }
        }

        if (!empty($arguments) && !in_array($userRole, $arguments, true)) {
            $dashboardMap = [
                'admin'   => '/admin/dashboard',
                'staf'    => '/staf/dashboard',
                'lurah'   => '/lurah/dashboard',
                'pemohon' => '/pemohon/dashboard',
            ];

            $target = $dashboardMap[$userRole] ?? '/login';
            return redirect()->to($target)->with('error', 'Akses ditolak: Anda tidak memiliki hak akses untuk halaman tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after controller execution
    }
}
