<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$input = $_GET['input'];

if ($input === 'deposit') {
    $depositController = new \Controller\DepositController();
    $depositController->processDeposit();
}

if ($input === 'login') {
    $loginController = new \Controller\LoginController();
    $loginController->userLogin();
}

if ($input === 'user') {
    $userController = new \Controller\UserController();
    $userController->registration();
}

if ($input === 'index') {
    include 'View/index.twig';
}
