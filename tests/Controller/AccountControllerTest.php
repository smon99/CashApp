<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\AccountController;
use App\Core\Container;
use App\Core\Redirect;
use App\Core\View;
use App\Model\AccountDTO;
use App\Model\AccountEntityManager;
use App\Model\AccountRepository;
use App\Core\AccountValidation;
use App\Core\Account\AccountValidationException;
use PHPUnit\Framework\TestCase;

class AccountControllerTest extends TestCase
{
    private AccountController $accountController;

    protected function setUp(): void
    {
        parent::setUp();

        $container = $this->createMock(Container::class);
        $view = $this->createMock(View::class);
        $repository = $this->createMock(AccountRepository::class);
        $entityManager = $this->createMock(AccountEntityManager::class);
        $validator = $this->createMock(AccountValidation::class);
        $redirect = $this->createMock(Redirect::class);

        $container->method('get')
            ->willReturnMap([
                [View::class, $view],
                [AccountRepository::class, $repository],
                [AccountEntityManager::class, $entityManager],
                [AccountValidation::class, $validator],
                [Redirect::class, $redirect],
            ]);

        $this->accountController = new AccountController($container);
    }

    public function testActionWithInvalidSession(): void
    {
        unset($_SESSION["loginStatus"]);

        $redirect = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        $redirect->expects($this->once())
            ->method('redirectTo')
            ->with('http://0.0.0.0:8000/?page=login');

        $container = $this->createMock(Container::class);
        $container->method('get')
            ->willReturnMap([
                [View::class, $this->createMock(View::class)],
                [AccountRepository::class, $this->createMock(AccountRepository::class)],
                [AccountEntityManager::class, $this->createMock(AccountEntityManager::class)],
                [AccountValidation::class, $this->createMock(AccountValidation::class)],
                [Redirect::class, $redirect],
            ]);

        $accountController = new AccountController($container);

        $accountController->action();
    }

}
