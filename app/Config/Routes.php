<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Autentikasi - Login, Logout, Register
    $routes->get('login', 'AuthController::index');
    $routes->post('login', 'AuthController::login');
    $routes->get('login/google', 'AuthController::loginGoogle');
    $routes->get('login/google/callback', 'AuthController::loginGoogleCallback');
    $routes->get('register', 'AuthController::register');
    $routes->post('register-process', 'AuthController::registerProcess');
    $routes->get('logout', 'AuthController::logout');




// Rute Dashboard dan Lainnya - Hanya dapat diakses jika sudah login

    $routes->get('/', 'DashboardController::index');

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
    $routes->post('dashboard/transaksi/(:num)/pembayaran', 'TransaksiController::addPembayaran/$1');
    $routes->get('dashboard/program/(:num)', 'DashboardController::viewProgramTransactions/$1');
    $routes->post('dashboard/update-dp/(:num)', 'DashboardController::updateDP/$1');
    $routes->get('dashboard/users', 'UserApprovalController::index');
    $routes->post('dashboard/users/(:num)/approve', 'UserApprovalController::approve/$1');
    $routes->post('dashboard/users/(:num)/reject', 'UserApprovalController::reject/$1');
    $routes->post('dashboard/users/(:num)/role', 'UserApprovalController::updateRole/$1');

    // Rute pengeditan transaksi
    $routes->get('dashboard/edit-transaction/(:num)', 'DashboardController::editTransaction/$1');
    $routes->post('dashboard/update-transaction/(:num)', 'DashboardController::updateTransaction/$1');

    // Rute pengeditan DP
    $routes->get('dashboard/edit-dp1/(:num)', 'DashboardController::editDP1/$1');
    $routes->get('dashboard/edit-dp2/(:num)', 'DashboardController::editDP2/$1');
    $routes->get('dashboard/edit-dp3/(:num)', 'DashboardController::editDP3/$1');
    $routes->post('dashboard/update-dp/(:num)', 'DashboardController::updateDP/$1');

    $routes->get('/keuangan', 'KeuanganController::index'); // Menampilkan daftar pengeluaran

    $routes->get('keuangan', 'KeuanganController::index');  // Menampilkan halaman pengeluaran
    $routes->post('keuangan/save', 'KeuanganController::save');  // Menyimpan pengeluaran
    $routes->get('keuangan/edit/(:num)', 'KeuanganController::edit/$1');
    $routes->post('keuangan/update/(:num)', 'KeuanganController::update/$1');
    
