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

$routes->get('/artikel', 'Artikel::home');
$routes->get('/artikel/detail/(:segment)', 'Artikel::detail/$1');
