<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);

$routes->get('/', 'Auth::index');

$routes->post('/login/auth', 'Auth::loginProcess');
$routes->get('/logout', 'Auth::logout');

$routes->group('admin', ['filter' => 'auth'], function ($routes) {

    $routes->get('/', 'Admin\Admin::index');
    $routes->get('dashboard', 'Admin\Admin::index');

    $routes->get('pendaftaran', 'Admin\Admin::pendaftaran');
    $routes->get('pendaftaran/edit/(:num)', 'Admin\Admin::edit_pendaftaran/$1');
    $routes->post('pendaftaran/update/(:num)', 'Admin\Admin::update_pendaftaran/$1');

    $routes->get('kedatangan', 'Admin\Kedatangan::index');
    $routes->post('kedatangan/confirm/(:num)', 'Admin\Kedatangan::confirm/$1');

    $routes->post('antrian/next/(:num)', 'Admin\Antrian::next/$1');
    $routes->get('antrian', 'Admin\Antrian::index');
    $routes->get('antrian/print', 'Admin\Antrian::print');
    $routes->get('antrian/export', 'Admin\Antrian::export');

    $routes->get('hasil-pemeriksaan', 'Admin\HasilPemeriksaan::index');
    $routes->get('hasil-pemeriksaan', 'Admin\HasilPemeriksaan::index');
    $routes->get('hasil-pemeriksaan/export', 'Admin\HasilPemeriksaan::export');
    $routes->get('hasil-pemeriksaan/print', 'Admin\HasilPemeriksaan::print');

    $routes->get('laporan-pembayaran', 'Admin\Laporan::pembayaran');
    $routes->get('laporan-pembayaran/export', 'Admin\Laporan::exportExcel');

    $routes->get('laporan/pengambilan-obat', 'Admin\Laporan::pengambilanObat');
    $routes->get('laporan/pengambilan-obat/print', 'Admin\Laporan::printPengambilanObat');
    $routes->get('laporan/pengambilan-obat/excel', 'Admin\Laporan::exportPengambilanObat');

    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');

    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->get('users', 'Admin\Users::index');

    $routes->get('jadwal', 'Admin\Jadwal::index');
    $routes->get('jadwal/create', 'Admin\Jadwal::create');
    $routes->post('jadwal/store', 'Admin\Jadwal::store');
    $routes->delete('jadwal/delete/(:num)', 'Admin\Jadwal::delete/$1');
});


$routes->group('apoteker', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Apoteker\Apoteker::index');
    $routes->get('detail/(:num)', 'Apoteker\Apoteker::detail/$1');
    $routes->post('pickup/(:num)', 'Apoteker\Apoteker::pickup/$1');
});

$routes->group('kasir', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Cashier\Kasir::index');
    $routes->get('detail/(:num)', 'Cashier\Kasir::detail/$1');
    $routes->post('bayar/(:num)', 'Cashier\Kasir::bayar/$1');
    $routes->post('prosesBayar', 'Cashier\Kasir::prosesBayar');
});

$routes->group('dokter', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Doctor\DoctorController::index');
    $routes->get('/', 'Doctor\DoctorController::index');
    $routes->get('examine/(:num)', 'Doctor\DoctorController::examine/$1');
    $routes->post('submitExamination', 'Doctor\DoctorController::submitExamination');
});

$routes->group('pasien', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Pasien\Pasien::index');
    $routes->get('booking', 'Pasien\Pasien::booking');
    $routes->post('booking/store', 'Pasien\Pasien::store');
    $routes->get('riwayat', 'Pasien\Pasien::riwayat');
    $routes->get('antrian', 'Pasien\Pasien::antrian');
    $routes->get('dashboard', 'Pasien\Pasien::dashboard');
    $routes->get('pasien/dashboard', '\App\Controllers\Pasien::index');
});


$routes->get('register', 'Pasien\Register::index');
$routes->post('pasien/register/process', 'Pasien\Register::process');

$routes->group('appointment', function ($routes) {
    $routes->get('getDoctorsByDept/(:num)', 'Pasien::getDoctorsByDept/$1');
    $routes->get('getSchedulesByDoctor/(:num)', 'Pasien::getSchedulesByDoctor/$1');
});

$routes->group('pendaftaran', function ($routes) {
    $routes->get('/', 'Pendaftaran\Pendaftaran::index');
    $routes->get('pasien', 'Pendaftaran\Pendaftaran::pasien');
    $routes->get('antrian', 'Pendaftaran\Pendaftaran::antrian');
    $routes->get('konfirmasi/(:num)', 'Pendaftaran\Pendaftaran::konfirmasi/$1');
});

$routes->get('reset-test', 'Auth::resetPasswordTest');
$routes->get('appointment/getDoctorsByDept/(:num)', 'Pendaftaran\AppointmentController::getDoctorsByDept/$1');
$routes->get('appointment/getSchedulesByDoctor/(:num)', 'Pendaftaran\AppointmentController::getSchedulesByDoctor/$1');
