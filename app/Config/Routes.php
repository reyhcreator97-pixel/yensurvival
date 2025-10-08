<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'User::index');
$routes->get('/user/index', 'User::index');
$routes->get('/user/dashboard', 'User::dashboard');
$routes->get('/register', 'Home::register');
$routes->get('/user', 'Home::user');

// $routes->group('', ['filter' => 'login'], static function($routes) {
//     // UTANG
//     $routes->get ('utang',           'Utang::index');
//     $routes->post('utang/store',     'Utang::store');
//     $routes->get ('utang/lunas/(:num)','Utang::lunas/$1');
//     $routes->post('utang/delete/(:num)','Utang::delete/$1');

//     // PIUTANG
//     $routes->get ('piutang',             'Piutang::index');
//     $routes->post('piutang/store',       'Piutang::store');
//     $routes->get ('piutang/lunas/(:num)','Piutang::lunas/$1');
//     $routes->post('piutang/delete/(:num)','Piutang::delete/$1');


// });

// --- UTANG
$routes->group('utang', ['filter' => 'login'], static function($routes) {
    $routes->get('/', 'Utang::index');
    $routes->post('store', 'Utang::store');
    $routes->post('lunas/(:num)', 'Utang::lunas/$1');
    $routes->post('delete/(:num)', 'Utang::delete/$1');
    $routes->post('storePembayaran', 'Utang::storePembayaran'); // ✅ tambahkan ini
});

// --- PIUTANG
$routes->group('piutang', ['filter' => 'login'], static function($routes) {
    $routes->get('/', 'Piutang::index');
    $routes->post('store', 'Piutang::store');
    $routes->post('lunas/(:num)', 'Piutang::lunas/$1');
    $routes->post('delete/(:num)', 'Piutang::delete/$1');
    $routes->post('storePembayaran', 'Piutang::storePembayaran'); // ✅ tambahkan ini
});



$routes->group('', static function($routes) {

    // Halaman setup kekayaan awal
    $routes->get ('kekayaan-awal',              'KekayaanAwal::index');
    $routes->post('kekayaan-awal/store',        'KekayaanAwal::store');
    $routes->post('kekayaan-awal/update',       'KekayaanAwal::update');
    $routes->post ('kekayaan-awal/delete/(:num)', 'KekayaanAwal::delete/$1');

});


$routes->group('transaksi', ['filter' => 'login'], static function($routes){
    $routes->get('/',               'Transaksi::index');
    $routes->post('store',          'Transaksi::store');      // pemasukan / pengeluaran
    $routes->post('transfer',       'Transaksi::transfer');   // pindah dana
    $routes->post('delete/(:num)',  'Transaksi::delete/$1');
    $routes->post('addAkun',        'Transaksi::addAkun');
});




$routes->get('/emas-indogold', 'EmasIndogold::index');
$routes->get('/kurs', 'KursDcom::index');

$routes->get('/admin', 'Admin::index', ['filter' => 'role:Admin']);
$routes->get('/admin/index', 'Admin::index', ['filter' => 'role:Admin']);
$routes->get('/admin/dashboard', 'Admin::dashboard', ['filter' => 'role:Admin']);
