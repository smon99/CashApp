<?php declare(strict_types=1);

use App\Core\Account\DayValidator;
use App\Core\Account\HourValidator;
use App\Core\Account\SingleValidator;

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$view = new App\Core\View(__DIR__ . '/src/View');
$accountMapper = new \App\Model\AccountMapper();
$accountValidator = new App\Core\AccountValidation(new DayValidator(), new HourValidator(), new SingleValidator());
$repository = new \App\Model\AccountRepository($accountMapper);
$entityManager = new \App\Model\AccountEntityManager();
$userMapper = new \App\Model\UserMapper();
$userRepository = new \App\Model\UserRepository($userMapper);
$userEntityManager = new \App\Model\UserEntityManager($userMapper);

$input = $_GET['input'] ?? '';

if ($input === 'deposit') {
    $accountController = new App\Controller\AccountController($view, $repository, $entityManager, $accountValidator);
    $accountController->processDeposit();
}

if ($input === 'login') {
    $loginController = new App\Controller\LoginController($view, new \App\Core\Redirect(), $userRepository);
    $loginController->userLogin();
}

if ($input === 'user') {
    $userController = new App\Controller\UserController($view, new \App\Core\Redirect(), $userEntityManager);
    $userController->registration();
}

if ($input === 'index') {
    include 'src/View/index.twig';
}
