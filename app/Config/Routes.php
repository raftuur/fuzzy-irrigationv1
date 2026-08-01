<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ======================
// ROUTES TANPA LOGIN (PUBLIC)
// ======================
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// ======================
// ROUTES DENGAN FILTER AUTH
// ======================
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');

    // Admin
    $routes->group('admin', function ($routes) {
        $routes->get('/', 'AdminController::index');
        $routes->get('create', 'AdminController::create');
        $routes->post('store', 'AdminController::store');
        $routes->get('edit/(:num)', 'AdminController::edit/$1');
        $routes->post('update/(:num)', 'AdminController::update/$1');
        $routes->get('delete/(:num)', 'AdminController::delete/$1');
        $routes->get('detail/(:num)', 'AdminController::detail/$1');
    });

    // Riwayat
    $routes->get('riwayat', 'RiwayatController::index');

    // Kontrol
    $routes->group('kontrol', function ($routes) {
        $routes->get('/', 'KontrolController::index');
        $routes->post('save', 'KontrolController::save');
    });

    // Setting
    $routes->group('setting', function ($routes) {
        $routes->get('/', 'SettingController::index');
        $routes->post('save', 'SettingController::save');
    });

    // Device
    $routes->group('device', function ($routes) {
        $routes->get('/', 'DeviceController::index');
        $routes->get('detail/(:segment)', 'DeviceController::detail/$1');
    });

    // Log
    $routes->get('log', 'LogAktivitasController::index');

});

// ======================
// ROUTES API (TANPA AUTH)
// ======================
$routes->group('api', function ($routes) {

    // ESP32 baca kontrol (GET) / Dashboard kirim kontrol (POST)
    $routes->match(['get', 'post'], 'kontrol', 'ApiController::kontrol');

    // ESP32 kirim sensor (POST)
    $routes->post('sensor', 'ApiController::sensor');

    // Dashboard ambil data (GET)
    $routes->get('dashboard', 'ApiController::dashboard');

});