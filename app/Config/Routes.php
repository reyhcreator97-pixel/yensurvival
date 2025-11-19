<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::login');
$routes->get('/register', 'Home::register');
$routes->get('/login', 'Home::login');

$routes->get('/user', 'Home::user');
$routes->get('/dream', 'Home::dream');

// $routes->get('/emas-indogold', 'EmasIndogold::index');
$routes->get('/kurs', 'KursDcom::index');
$routes->get('/goldprice', 'GoldPrice::index');

// check coupon user
$routes->get('coupon/check', 'Coupon::check');

//Checkout dari landing page (tanpa login)
$routes->group('', ['filter' => 'optionalLogin'], function ($routes) {
    $routes->get('checkout-form', 'user\Checkout::index');
    $routes->post('checkout-form/process', 'user\Checkout::process');
    $routes->get('checkout-form/thankyou', 'user\Checkout::thankyou');
});


// ===============================
// 📦 ROUTES UNTUK USER
// ===============================
$routes->group('user', ['filter' => 'login'], function ($routes) {
    $routes->get('index', 'user::index');
    $routes->get('dashboard', 'user::dashboard');
    $routes->get('panel', 'user\Panel::index');
    $routes->post('panel/changePassword', 'user\Panel::changePassword');
    $routes->get('dashboard/chart', 'user\Dashboard::getChartData');
    $routes->get('tutorials', 'user\Tutorial::index');
    $routes->get('dashboard/category-chart', 'user\Dashboard::getCategoryChart');
    $routes->get('dashboard/finance-data', 'user\Dashboard::getFinanceData');
});

// ===============================
// 📦 ROUTES UNTUK PAGE SUBSCRIPTION
// ===============================
$routes->group('user', ['filter' => 'login'], function ($routes) {
    $routes->get('subscription', 'User\Subscription::index');
    $routes->get('subscription/buy/(:segment)', 'User\Subscription::buy/$1');
    $routes->get('subscription/upgrade', 'User\Subscription::upgrade');
    $routes->get('subscription/renew', 'User\Subscription::renew');
    $routes->get('subscription/checkout/(:segment)', 'User\Subscription::checkout/$1');
});


// ===============================
// 📦 ROUTES UNTUK PAGE ASET
// ===============================
$routes->group('aset', ['filter' => 'buyer'], function ($routes) {
    $routes->get('/', 'Aset::index');               // Halaman utama daftar aset
    $routes->post('store', 'Aset::store');          // Tambah aset baru
    $routes->post('updateNilai', 'Aset::updateNilai'); // Update nilai sekarang
    $routes->post('jual', 'Aset::jual');            // Jual aset
    $routes->get('delete/(:num)', 'Aset::delete/$1'); // Hapus aset
});


// ===============================
// 📦 ROUTES UNTUK PAGE UTANG
// ===============================
$routes->group('utang', ['filter' => 'buyer'], static function ($routes) {
    $routes->get('/', 'Utang::index');
    $routes->post('store', 'Utang::store');
    $routes->post('lunas/(:num)', 'Utang::lunas/$1');
    $routes->get('delete/(:num)', 'Utang::delete/$1');
    $routes->post('storePembayaran', 'Utang::storePembayaran'); // ✅ tambahkan ini
});

// ===============================
// 📦 ROUTES UNTUK PAGE PIUTANG
// ===============================
$routes->group('piutang', ['filter' => 'buyer'], static function ($routes) {
    $routes->get('/', 'Piutang::index');
    $routes->post('store', 'Piutang::store');
    $routes->post('lunas/(:num)', 'Piutang::lunas/$1');
    $routes->get('delete/(:num)', 'Piutang::delete/$1');
    $routes->post('storePembayaran', 'Piutang::storePembayaran'); // ✅ tambahkan ini
});

// =============================
// 📈 ROUTES: INVESTASI
// =============================
$routes->group('investasi', ['filter' => 'buyer'], function ($routes) {
    $routes->get('/', 'Investasi::index');                   // Halaman utama
    $routes->post('store', 'Investasi::store');              // Tambah investasi baru
    $routes->post('updateNilai', 'Investasi::updateNilai');  // Update nilai sekarang
    $routes->post('jual', 'Investasi::jual');                // Jual investasi
    $routes->get('delete/(:num)', 'Investasi::delete/$1');   // Hapus investasi
    $routes->get('total', 'Investasi::getTotalInvestasi');
});

// =============================
// 📈 ROUTES: KEKAYAAN AWAL
// =============================

$routes->group('kekayaan-awal', ['filter' => 'buyer'], static function ($routes) {

    $routes->get('/',              'KekayaanAwal::index');
    $routes->post('store',        'KekayaanAwal::store');
    $routes->post('update',       'KekayaanAwal::update');
    $routes->get('delete/(:num)', 'KekayaanAwal::delete/$1');
});

// =============================
// 📈 ROUTES: TRANSAKSI
// =============================

$routes->group('transaksi', ['filter' => 'buyer'], static function ($routes) {
    $routes->get('/',               'Transaksi::index');
    $routes->post('store',          'Transaksi::store');      // pemasukan / pengeluaran
    $routes->post('transfer',       'Transaksi::transfer');   // pindah dana
    $routes->get('delete/(:num)',  'Transaksi::delete/$1');
    $routes->post('addAkun',        'Transaksi::addAkun');
});

// ======================
// 👤 USER DEVELOPMENT
// ======================
$routes->group('user', ['filter' => 'login'], function ($routes) {
    // halaman changelog publik (read-only)
    $routes->get('development', 'user\Development::index');
});



// ===============================
// 📦 ADMIN ROUTES
// ===============================
$routes->group('admin', ['filter' => 'role:Admin'], function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('index', 'Admin::index');
    $routes->get('dashboard', 'admin\Dashboard::index');


    // =========================
    // 🔹 ADMIN - COUPONS
    // =========================  
    $routes->get('coupons', 'Admin\Coupons::index');
    $routes->get('coupons/create', 'Admin\Coupons::create');
    $routes->post('coupons/store', 'Admin\Coupons::store');
    $routes->get('coupons/delete/(:num)', 'Admin\Coupons::delete/$1');

    // =========================
    // 🔹 ADMIN - USERS
    // =========================
    $routes->get('users', 'admin\Users::index');
    $routes->get('users/suspend/(:num)', 'admin\Users::suspend/$1');
    $routes->get('users/activate/(:num)', 'admin\Users::activate/$1');
    $routes->get('users/resetPassword/(:num)', 'admin\Users::resetPassword/$1');
    $routes->get('users/delete/(:num)', 'admin\Users::delete/$1');

    // =========================
    // 🔹 ADMIN - SUBSCRIPTION
    // =========================
    $routes->get('subscription', 'admin\Subscription::index');
    $routes->get('subscription/add', 'admin\Subscription::add');
    $routes->post('subscription/create', 'admin\Subscription::create');
    $routes->get('subscription/edit/(:num)', 'admin\Subscription::edit/$1');
    $routes->post('subscription/update/(:num)', 'admin\Subscription::update/$1');
    $routes->get('subscription/delete/(:num)', 'admin\Subscription::delete/$1');

    $routes->get('subscription/activate/(:num)', 'admin\Subscription::activate/$1');
    $routes->get('subscription/cancel/(:num)', 'admin\Subscription::cancel/$1');
    $routes->get('subscription/extend/(:num)', 'admin\Subscription::extend/$1');



    // =========================
    // 🔹 ADMIN - TRANSAKSI
    // =========================
    $routes->get('transaksi', 'admin\Transaksi::index');

    // =========================
    // 🔹 ADMIN - SETTINGS ROUTES
    // =========================

    // Halaman utama pengaturan
    $routes->get('settings', 'admin\Settings::index');

    // Simpan perubahan pengaturan
    $routes->post('settings/save', 'admin\Settings::save');

    // Buat backup manual
    $routes->get('settings/backup', 'admin\Settings::backup');

    // Download backup terbaru
    $routes->get('settings/download', 'admin\Settings::downloadBackup');

    // Auto backup route
    $routes->get('settings/auto-backup', 'admin\Settings::autoBackup');

    // =========================
    // 🔹 ADMIN - Income
    // =========================

    $routes->get('income', 'Admin\Income::index');
    $routes->get('income/approve/(:num)', 'Admin\Income::approve/$1');

    // =========================
    // 🔹 ADMIN - Log
    // =========================
    $routes->get('logs', 'admin\Logs::index');

    // ======================
    //  ADMIN DEVELOPMENT
    // ======================
    $routes->get('development', 'admin\Development::index');
    $routes->post('development/store', 'admin\Development::store');
    $routes->get('development/edit/(:num)', 'admin\Development::edit/$1');
    $routes->post('development/update/(:num)', 'admin\Development::update/$1');
    $routes->get('development/delete/(:num)', 'admin\Development::delete/$1');
    $routes->post('development/save', 'admin\Development::save');

    // ======================
    //  ADMIN Video Tutorial
    // ======================
    $routes->get('tutorials', 'admin\Tutorial::index');
    $routes->post('tutorials/store', 'admin\Tutorial::store');
    $routes->post('tutorials/update/(:num)', 'admin\Tutorial::update/$1');
    $routes->get('tutorials/delete/(:num)', 'admin\Tutorial::delete/$1');

    // ======================
    //  ADMIN Kategori Transaksi
    // ======================

    $routes->get('kategori-transaksi', 'admin\TransactionCategory::index');
    $routes->post('kategori-transaksi/save', 'admin\TransactionCategory::save');
    $routes->post('kategori-transaksi/update/(:num)', 'admin\TransactionCategory::update/$1');
    $routes->get('kategori-transaksi/delete/(:num)', 'admin\TransactionCategory::delete/$1');
});
