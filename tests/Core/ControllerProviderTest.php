<?php declare(strict_types=1);

namespace Test\Core;

use App\Controller\AccountController;
use App\Controller\LoginController;
use App\Controller\ErrorController;
use App\Controller\TransactionController;
use App\Controller\UserController;
use App\Core\ControllerProvider;
use PHPUnit\Framework\TestCase;

class ControllerProviderTest extends TestCase
{
    public function testGetList(): void
    {
        $provider = new ControllerProvider();
        $controllerList = $provider->getList();

        $this->assertIsArray($controllerList);
        $this->assertCount(5, $controllerList);

        $this->assertArrayHasKey('account', $controllerList);
        $this->assertArrayHasKey('login', $controllerList);
        $this->assertArrayHasKey('user', $controllerList);
        $this->assertArrayHasKey('unknown', $controllerList);
        $this->assertArrayHasKey('transaction', $controllerList);

        $this->assertSame(AccountController::class, $controllerList['account']);
        $this->assertSame(LoginController::class, $controllerList['login']);
        $this->assertSame(UserController::class, $controllerList['user']);
        $this->assertSame(ErrorController::class, $controllerList['unknown']);
        $this->assertSame(TransactionController::class, $controllerList['transaction']);
    }
}