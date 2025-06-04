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

$routes->get('/questions', 'questions::index');
$routes->get('/questions/(:segment)', 'questions::show/$1');
$routes->post('/questions/(:segment)', 'questions::submit/$1');

$routes->get('anxiety', 'Pages::anxiety'); // Halaman form pertanyaan
$routes->post('hasil/anxiety', 'Hasil::anxiety'); // Proses hasil


$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);
