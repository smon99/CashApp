<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$view = new App\Core\View(__DIR__ . '/src/View');
$accountMapper = new \App\Model\AccountMapper();
$validator = new App\Core\AccountValidation();
$repository = new \App\Model\AccountRepository($accountMapper);
$entityManager = new \App\Model\AccountEntityManager();
$userMapper = new \App\Model\UserMapper();
$userRepository = new \App\Model\UserRepository($userMapper);
$userEntityManager = new \App\Model\UserEntityManager($userMapper);

$input = $_GET['input'] ?? '';

if ($input === 'deposit') {
    $depositController = new App\Controller\DepositController($view, $repository, $entityManager, $validator);
    $depositController->processDeposit();
}

if ($input === 'login') {
    $loginController = new App\Controller\LoginController($view, new \App\Core\Redirect(), $userRepository);
    $loginController->userLogin();
}

if ($input === 'user') {
    $userController = new App\Controller\UserController($view, new \App\Core\Redirect(), $userRepository, $userEntityManager, $userMapper);
    $userController->registration();
}

if ($input === 'index') {
    include 'src/View/index.twig';
}
