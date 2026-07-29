<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {

    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('admin', 'AdminController::index');

    $routes->get('admin/create', 'AdminController::create');
    $routes->post('admin/store', 'AdminController::store');

    $routes->get('admin/edit/(:num)', 'AdminController::edit/$1');
    $routes->post('admin/update/(:num)', 'AdminController::update/$1');

    $routes->get('admin/delete/(:num)', 'AdminController::delete/$1');

    $routes->get('admin/detail/(:num)', 'AdminController::detail/$1');

    $routes->get('riwayat', 'RiwayatController::index');

    $routes->get('kontrol', 'KontrolController::index');

    $routes->get('setting', 'SettingController::index');

    $routes->post('setting/save', 'SettingController::save');
    $routes->post('kontrol/save','KontrolController::save');
    $routes->get('device', 'DeviceController::index');
    $routes->get('device/detail/(:segment)', 'DeviceController::detail/$1');

    $routes->get('log', 'LogAktivitasController::index');

});

$routes->group('api', function ($routes) {

    $routes->match(['get','post'], 'sensor', 'ApiController::sensor');

    $routes->match(['get','post'], 'kontrol', 'ApiController::kontrol');

    $routes->get('dashboard', 'ApiController::dashboard');

});