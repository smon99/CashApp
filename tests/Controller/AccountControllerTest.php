<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AccountController;
use App\Core\Container;
use App\Core\View;
use App\Model\AccountRepository;
use App\Core\AccountValidation;
use App\Core\Account\AccountValidationException;
use App\Model\AccountEntityManager;

class AccountControllerTest extends TestCase
{
    private Container|\PHPUnit\Framework\MockObject\MockObject $container;
    private View|\PHPUnit\Framework\MockObject\MockObject $view;
    private AccountRepository|\PHPUnit\Framework\MockObject\MockObject $repository;
    private AccountEntityManager|\PHPUnit\Framework\MockObject\MockObject $entityManager;
    private AccountValidation|\PHPUnit\Framework\MockObject\MockObject $validator;
    private AccountController $controller;

    protected function setUp(): void
    {
        $this->container = $this->createMock(Container::class);
        $this->view = $this->createMock(View::class);
        $this->repository = $this->createMock(AccountRepository::class);
        $this->entityManager = $this->createMock(AccountEntityManager::class);
        $this->validator = $this->createMock(AccountValidation::class);

        $this->container->method('get')
            ->willReturnMap([
                [View::class, $this->view],
                [AccountRepository::class, $this->repository],
                [AccountEntityManager::class, $this->entityManager],
                [AccountValidation::class, $this->validator],
            ]);

        $this->controller = new AccountController($this->container);
    }

    public function testActionWithValidInput(): void
    {
        $_POST["amount"] = "100.50";

        $this->validator->expects($this->once())
            ->method('collectErrors');
        $this->repository->expects($this->once())
            ->method('calculateBalance')
            ->willReturn(500.75);
        $this->entityManager->expects($this->once())
            ->method('saveDeposit');

        $response = $this->controller->action();

        $this->assertInstanceOf(View::class, $response);
    }

    public function testActionWithInvalidInput(): void
    {
        $_POST["amount"] = "invalid_amount";

        $this->validator->expects($this->once())
            ->method('collectErrors')
            ->willThrowException(new AccountValidationException("Validation failed"));

        $response = $this->controller->action();

        $this->assertInstanceOf(View::class, $response);
    }

    public function testSessionCreate(): void
    {
        session_start();
        $_SESSION['loginStatus'] = true;

        $this->controller->action();

        self::assertNull($_SESSION['username']);
        session_destroy();
    }

    public function testSessionDestroy(): void
    {
        session_start();
        $_POST['logout'] = true;

        $this->controller->action();

        self::assertNull($_SESSION['loginStatus']);
    }
}
