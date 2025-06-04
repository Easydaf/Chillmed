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

// HAPUS RUTE INI JIKA ANDA MENGKONSOLIDASI KE QUESTIONS CONTROLLER
// $routes->get('anxiety', 'Pages::anxiety');
// $routes->post('hasil/anxiety', 'Hasil::anxiety');

$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

// Anda bisa menambahkan rute untuk halaman statis lain di Pages Controller jika diperlukan
// $routes->get('/about', 'Pages::about');
