<?php
require __DIR__ . '/../app/autoload.php';
require __DIR__ . '/../app/helpers.php';

use App\Controllers\AgendaController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\TugasController;
use App\Core\Router;

$router = new Router();

$router->get('/', [DashboardController::class, 'index']);

$router->get('/login', [AuthController::class, 'formLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/registrasi', [AuthController::class, 'formRegistrasi']);
$router->post('/registrasi', [AuthController::class, 'registrasi']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/tugas', [TugasController::class, 'index']);
$router->get('/tugas/form', [TugasController::class, 'form']);
$router->post('/tugas/simpan', [TugasController::class, 'simpan']);
$router->post('/tugas/ubah', [TugasController::class, 'ubah']);
$router->post('/tugas/hapus', [TugasController::class, 'hapus']);
$router->post('/tugas/status', [TugasController::class, 'ubahStatus']);

$router->get('/agenda', [AgendaController::class, 'index']);
$router->post('/agenda/simpan', [AgendaController::class, 'simpan']);
$router->post('/agenda/ubah', [AgendaController::class, 'ubah']);
$router->post('/agenda/hapus', [AgendaController::class, 'hapus']);

$router->jalankan($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
