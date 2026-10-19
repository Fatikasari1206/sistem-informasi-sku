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
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $session->get('role');

        if (!empty($arguments) && !in_array($userRole, $arguments, true)) {
            // Redirect to appropriate dashboard based on actual role
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
