<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==================================================
// 1. PUBLIC ROUTES (USER/PENONTON) - AKSES BEBAS
// ==================================================

// Halaman Depan langsung ke Jadwal User
$routes->get('/', 'Jadwal::index'); 

// Menu Navigasi User
$routes->get('jadwal', 'Jadwal::index');
$routes->get('klasemen', 'Klasemen::index');

// Halaman Detail Live Match (Penonton)
$routes->get('match/(:num)', 'Jadwal::detail/$1');


// ==================================================
// 2. AUTHENTICATION (LOGIN & REGISTER)
// ==================================================

$routes->get('login', 'Auth::index');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');

$routes->get('register', 'Auth::register');
$routes->post('login/process_register', 'Auth::process_register');


// ==================================================
// 3. API ROUTES (DATA JSON UNTUK AJAX)
// ==================================================

// Endpoint untuk mengambil data realtime skor & event
$routes->get('api/live/(:num)', 'Api::live_match/$1');


// ==================================================
// 4. ADMIN DASHBOARD (DIPROTEKSI FILTER 'ADMIN')
// ==================================================

// Group ini otomatis mengecek login & role admin
$routes->group('dashboard', ['filter' => 'admin', 'namespace' => 'App\Controllers\Admin'], function($routes) {
    
    // A. Dashboard Utama
    $routes->get('/', 'Dashboard::index');

    // B. Manajemen Data Tim (CRUD)
    $routes->get('tim', 'Tim::index');                // List
    $routes->get('tim/tambah', 'Tim::tambah');        // Form Tambah
    $routes->post('tim/simpan', 'Tim::simpan');       // Proses Simpan
    $routes->get('tim/edit/(:num)', 'Tim::edit/$1');  // Form Edit
    $routes->post('tim/update/(:num)', 'Tim::update/$1'); // Proses Update
    $routes->delete('tim/hapus/(:num)', 'Tim::hapus/$1'); // Hapus

    // MANAJEMEN PEMAIN (CRUD)
    $routes->get('pemain', 'Pemain::index');
    $routes->get('pemain/tambah', 'Pemain::tambah');
    $routes->post('pemain/simpan', 'Pemain::simpan');
    $routes->get('pemain/edit/(:num)', 'Pemain::edit/$1');
    $routes->post('pemain/update/(:num)', 'Pemain::update/$1');
    $routes->delete('pemain/hapus/(:num)', 'Pemain::hapus/$1');

    // C. Manajemen Jadwal (CRUD)
    $routes->get('jadwal', 'Jadwal::index');
    $routes->get('jadwal/tambah', 'Jadwal::tambah');
    $routes->post('jadwal/simpan', 'Jadwal::simpan');
    $routes->get('jadwal/edit/(:num)', 'Jadwal::edit/$1');
    $routes->post('jadwal/update/(:num)', 'Jadwal::update/$1');
    $routes->delete('jadwal/hapus/(:num)', 'Jadwal::hapus/$1');

    // D. Input Skor & Live Console
    // 1. Halaman Pilih Pertandingan
    $routes->get('skor', 'Skor::index');

    // 2. Live Console (Remote Control)
    $routes->get('live/(:num)', 'LiveMatch::index/$1');
    
    // 3. Aksi Kontrol Pertandingan
    $routes->get('live/start/(:num)', 'LiveMatch::start/$1');         // Kickoff
    $routes->get('live/halftime/(:num)', 'LiveMatch::halftime/$1');   // HT
    $routes->get('live/second_half/(:num)', 'LiveMatch::second_half/$1'); // Mulai Babak 2
    $routes->get('live/end/(:num)', 'LiveMatch::end/$1');             // FT
    
    // 4. Input Event (Gol/Kartu) & Koreksi
    $routes->post('live/event', 'LiveMatch::add_event');
    $routes->get('live/delete_event/(:num)/(:num)', 'LiveMatch::delete_event/$1/$2'); // id_event / id_jadwal
});