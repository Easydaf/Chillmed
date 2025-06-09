<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');
$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::attemptRegister');

$routes->post('login', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');

$routes->get('/chatbot', 'ChatbotController::index');
$routes->post('/chatbot/message', 'ChatbotController::message');

// Rute untuk menampilkan daftar pertanyaan dan form pertanyaan spesifik
$routes->get('/questions', 'Questions::index');
$routes->get('/questions/(:segment)', 'Questions::show/$1');
$routes->post('/questions/(:segment)', 'Questions::submit/$1');

// Rute untuk menampilkan hasil kuesioner
$routes->get('/hasil/(:segment)', 'Hasil::index/$1');

$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

// Rute untuk Halaman Admin (membutuhkan autentikasi DAN role admin)
$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {

    // --- Rute POST/MATCH (CRUD) - Paling atas dan spesifik ---
    // Manajemen Quotes
    $routes->match(['GET', 'POST'], 'quotes/add', 'AdminController::addQuote'); // UBAH INI
    $routes->post('quotes/edit/(:num)', 'AdminController::editQuote/$1');
    $routes->post('quotes/delete/(:num)', 'AdminController::deleteQuote/$1');

    // Manajemen Artikel
    $routes->match(['GET', 'POST'], 'articles/add', 'AdminController::addArticle');
    $routes->match(['GET', 'POST'], 'articles/edit/(:num)', 'AdminController::editArticle/$1');
    $routes->post('articles/delete/(:num)', 'AdminController::deleteArticle/$1');

    // Manajemen Users
    $routes->post('users/edit-role/(:num)', 'AdminController::editUserRole/$1'); // AJAX POST untuk edit role
    $routes->post('users/delete/(:num)', 'AdminController::deleteUser/$1'); // POST dari form HTML untuk hapus
    $routes->get('users', 'AdminController::users'); // GET untuk menampilkan daftar user

    // --- Rute GET (Untuk menampilkan halaman) - Setelah rute POST/MATCH ---
    $routes->get('/', 'AdminController::index'); // Dashboard Admin
    $routes->get('quotes', 'AdminController::quotes'); // Tampilkan daftar quotes
    $routes->get('articles', 'AdminController::articles'); // Tampilkan daftar artikel
    $routes->get('users', 'AdminController::users'); // Tampilkan daftar user
});

$routes->get('/artikel', 'Artikel::home');
$routes->get('/artikel/detail/(:segment)', 'Artikel::detail/$1');