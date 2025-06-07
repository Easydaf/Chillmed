<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login'); // halaman login utama
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::attemptRegister');

$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

$routes->get('/chatbot', 'ChatbotController::index');
$routes->post('/chatbot/message', 'ChatbotController::message');

// Rute untuk menampilkan daftar pertanyaan dan form pertanyaan spesifik
$routes->get('/questions', 'Questions::index');
$routes->get('/questions/(:segment)', 'Questions::show/$1'); // Menampilkan form kuesioner (e.g., /questions/anxiety)
$routes->post('/questions/(:segment)', 'Questions::submit/$1'); // Memproses submit form kuesioner (e.g., /questions/anxiety POST)

// Rute untuk menampilkan hasil kuesioner
// Rute ini akan mengarahkan ke HasilController::index() dengan kategori sebagai parameter
$routes->get('/hasil/(:segment)', 'Hasil::index/$1'); // <-- TAMBAHKAN ATAU BETULKAN RUTE INI

// HAPUS RUTE INI JIKA
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {
    $routes->get('/', 'AdminController::index'); // Dashboard Admin

    // Manajemen Quotes
    $routes->get('quotes', 'AdminController::quotes'); // Tampilkan daftar quotes
    $routes->post('quotes/add', 'AdminController::addQuote'); // Tambah quote (via AJAX)
    $routes->post('quotes/edit/(:num)', 'AdminController::editQuote/$1'); // Edit quote (via AJAX)
    $routes->post('quotes/delete/(:num)', 'AdminController::deleteQuote/$1'); // Hapus quote (via AJAX)

    // Manajemen Artikel
    $routes->get('articles', 'AdminController::articles'); // Tampilkan daftar artikel
    $routes->match(['get', 'post'], 'articles/add', 'AdminController::addArticle'); // Tambah artikel (GET untuk form, POST untuk submit)
    $routes->match(['get', 'post'], 'articles/edit/(:num)', 'AdminController::editArticle/$1'); // Edit artikel (GET untuk form, POST untuk submit)
    $routes->post('articles/delete/(:num)', 'AdminController::deleteArticle/$1'); // Hapus artikel (via AJAX)
});

$routes->get('/artikel', 'Artikel::home');
$routes->get('/artikel/detail/(:segment)', 'Artikel::detail/$1');
