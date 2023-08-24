<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$input = $_GET['input'];

if ($input === 'deposit') {
    $depositController = new \Controller\DepositController();
    $depositController->processDeposit();
} elseif ($input === 'login') {
    $loginController = new \Controller\LoginController();
    $loginController->userLogin();
} elseif ($input === 'user') {
    $userController = new \Controller\UserController();
    //$userController->handleRegistration();
    $userController->registration();
} else {
    include 'View/index.twig';
}