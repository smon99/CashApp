<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$view = new App\Core\View(__DIR__ . '/src/View');

$input = $_GET['input'] ?? '';

if ($input === 'deposit') {
    $depositController = new App\Controller\DepositController($view);
    $depositController->processDeposit();
}

if ($input === 'login') {
    $loginController = new App\Controller\LoginController($view);
    $loginController->userLogin();
}

if ($input === 'user') {
    $userController = new App\Controller\UserController($view);
    $userController->registration();
}

if ($input === 'index') {
    include 'src/View/index.twig';
}
