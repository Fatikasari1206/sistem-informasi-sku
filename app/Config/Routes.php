<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Landing Page Publik
$routes->get('/', 'Home::index');

// Autentikasi (Publik / Tamu)
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('admin', 'Auth::adminLogin');
$routes->post('admin', 'Auth::processAdminLogin');
$routes->get('register', 'Auth::register');
$routes->get('register/cek-nik', 'Auth::cekNik');
$routes->post('register', 'Auth::processRegister');
$routes->get('logout', 'Auth::logout');

// Group Admin (Hanya role: admin)
$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('aparatur', 'Admin\Aparatur::index');
    $routes->post('aparatur', 'Admin\Aparatur::store');
    $routes->post('aparatur/(:num)/update', 'Admin\Aparatur::update/$1');
    $routes->post('aparatur/(:num)/status', 'Admin\Aparatur::toggleStatus/$1');
    $routes->post('aparatur/(:num)/delete', 'Admin\Aparatur::delete/$1');
    $routes->get('master-nik', 'Admin\MasterNik::index');
    $routes->post('master-nik', 'Admin\MasterNik::store');
    $routes->post('master-nik/(:num)/update', 'Admin\MasterNik::update/$1');
    $routes->post('master-nik/(:num)/delete', 'Admin\MasterNik::delete/$1');
});

// Group Staf Kalurahan (Hanya role: staf)
$routes->group('staf', ['filter' => 'role:staf'], static function ($routes) {
    $routes->get('dashboard', 'Staf\Dashboard::index');
    $routes->get('antrian', 'Staf\Dashboard::antrian');
    $routes->get('verifikasi', 'Staf\Dashboard::verifikasi');
    $routes->get('periksa/(:num)', 'Staf\Dashboard::periksa/$1');
    $routes->post('periksa/(:num)', 'Staf\Dashboard::prosesVerifikasi/$1');
    $routes->get('dokumen/(:num)', 'Staf\Dashboard::dokumen/$1');
    $routes->get('penomoran', 'Staf\Dashboard::penomoran');
    $routes->post('penomoran/(:num)', 'Staf\Dashboard::terbitkanNomor/$1');
});

// Group Lurah (Hanya role: lurah)
$routes->group('lurah', ['filter' => 'role:lurah'], static function ($routes) {
    $routes->get('dashboard', 'Lurah\Dashboard::index');
    $routes->get('antrian', 'Lurah\Dashboard::antrian');
    $routes->get('pengesahan', 'Lurah\Dashboard::pengesahan');
    $routes->get('tte/(:num)', 'Lurah\Dashboard::tte/$1');
    $routes->post('tte/(:num)', 'Lurah\Dashboard::prosesTte/$1');
    $routes->get('arsip', 'Lurah\Dashboard::arsip');
});

// Group Pemohon (Hanya role: pemohon)
$routes->group('pemohon', ['filter' => 'role:pemohon'], static function ($routes) {
    $routes->get('dashboard', 'Pemohon\Dashboard::index');
    $routes->get('sku/baru', 'Pemohon\Sku::baru');
    $routes->post('sku/baru', 'Pemohon\Sku::storeBaru');
    $routes->get('sku/perpanjangan', 'Pemohon\Sku::perpanjangan');
    $routes->post('sku/perpanjangan', 'Pemohon\Sku::storePerpanjangan');
    $routes->get('sku/detail/(:num)', 'Pemohon\Sku::detail/$1');
    $routes->get('sku/cetak/(:num)', 'Pemohon\Sku::cetak/$1');
    $routes->get('sku/dokumen/(:num)', 'Pemohon\Sku::dokumen/$1');
    $routes->get('sku/perbaiki/(:num)', 'Pemohon\Sku::perbaiki/$1');
    $routes->post('sku/perbaiki/(:num)', 'Pemohon\Sku::updateRevisi/$1');
});
