<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Autentikasi - Login, Logout, Register
$routes->group('', function ($routes) {
    $routes->get('login', 'AuthController::login');                    // Halaman login
    $routes->post('login', 'AuthController::loginProcess');            // Proses login
    $routes->get('logout', 'AuthController::logout');                  // Logout
    $routes->get('register', 'AuthController::register');              // Halaman register
    $routes->post('register-process', 'AuthController::registerProcess'); // Proses register
});

// Rute Utama - Mengarahkan root URL ke login jika belum login
$routes->get('/', 'AuthController::login'); // Arahkan ke login saat pertama kali diakses

// Rute Dashboard dan Lainnya - Hanya dapat diakses jika sudah login
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Dashboard sebagai halaman utama setelah login
    $routes->get('dashboard', 'DashboardController::index');           // Halaman dashboard utama
    $routes->get('dashboard/add-jamaah', 'JamaahController::addJamaah');    // Form tambah jamaah
    $routes->post('dashboard/add-jamaah', 'JamaahController::saveJamaah'); // Simpan data jamaah

    // Rute untuk program
    $routes->get('dashboard/add-program', 'ProgramController::addProgram');    // Form tambah program
    $routes->post('dashboard/add-program', 'ProgramController::saveProgram');  // Simpan data program

    // Rute untuk transaksi
    $routes->get('dashboard/add-transaksi', 'TransaksiController::addTransaksi');    // Form tambah transaksi
    $routes->post('dashboard/add-transaksi', 'TransaksiController::saveTransaksi');  // Simpan data transaksi
    $routes->get('dashboard/program/(:num)', 'DashboardController::viewProgramTransactions/$1');
    $routes->post('dashboard/update-dp/(:num)', 'DashboardController::updateDP/$1');

    // Rute pengeditan transaksi
    $routes->get('dashboard/edit-transaction/(:num)', 'DashboardController::editTransaction/$1');
    $routes->post('dashboard/update-transaction/(:num)', 'DashboardController::updateTransaction/$1');

    // Rute pengeditan DP
    $routes->get('dashboard/edit-dp1/(:num)', 'DashboardController::editDP1/$1');
    $routes->get('dashboard/edit-dp2/(:num)', 'DashboardController::editDP2/$1');
    $routes->get('dashboard/edit-dp3/(:num)', 'DashboardController::editDP3/$1');
    $routes->post('dashboard/update-dp/(:num)', 'DashboardController::updateDP/$1');

    // Rute untuk keuangan
    $routes->get('keuangan', 'KeuanganController::index');
});
