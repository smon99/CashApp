<?php declare(strict_types=1);

use App\Core\Container;
use App\Core\View;

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();

$container = new Container();

$dependencyProvider = new \App\Core\DependencyProvider();
$dependencyProvider->provide($container);

$controllerProvider = new \App\Core\ControllerProvider();
$page = $_GET['page'] ?? '';

$controller = new \App\Controller\ErrorController($container);

foreach ($controllerProvider->getList() as $key => $controllerClass) {
    if ($key === $page) {
        $controllerCheck = new $controllerClass($container);
        if ($controllerCheck instanceof \App\Controller\ControllerInterface) {
            $controller = $controllerCheck;
            break;
        }
    }
}

$controller->action();

$view = $container->get(View::class);
$view->display();
