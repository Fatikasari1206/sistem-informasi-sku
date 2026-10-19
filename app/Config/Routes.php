<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Landing Page Publik
$routes->get('/', 'Home::index');

// Autentikasi (Publik / Tamu)
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');
$routes->get('logout', 'Auth::logout');

// Group Admin (Hanya role: admin)
$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
});

// Group Staf Kalurahan (Hanya role: staf)
$routes->group('staf', ['filter' => 'role:staf'], static function ($routes) {
    $routes->get('dashboard', 'Staf\Dashboard::index');
});

// Group Lurah (Hanya role: lurah)
$routes->group('lurah', ['filter' => 'role:lurah'], static function ($routes) {
    $routes->get('dashboard', 'Lurah\Dashboard::index');
});

// Group Pemohon (Hanya role: pemohon)
$routes->group('pemohon', ['filter' => 'role:pemohon'], static function ($routes) {
    $routes->get('dashboard', 'Pemohon\Dashboard::index');
});
