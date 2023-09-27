<?php declare(strict_types=1);

namespace Test\Controller;

use App\Core\Account\DayValidator;
use App\Core\Account\HourValidator;
use App\Core\Account\SingleValidator;
use App\Model\AccountMapper;
use PHPUnit\Framework\TestCase;
use App\Controller\AccountController;
use App\Core\ViewInterface;
use App\Model\AccountRepository;
use App\Model\AccountEntityManager;
use App\Core\AccountValidation;

class AccountControllerTest extends TestCase
{
    private $view;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->view = $this->createMock(ViewInterface::class);
        $this->entityManager = $this->createMock(AccountEntityManager::class);
        $this->repository = new AccountRepository($accountMapper = new AccountMapper());
        $this->validator = new AccountValidation(new DayValidator(), new HourValidator(), new SingleValidator());
    }

    public function testProcessDepositWithValidInput(): void
    {
        $_POST["amount"] = '1';
        $accountController = new AccountController($this->view, $this->repository, $this->entityManager, $this->validator);
        $processDeposit = $accountController->action();

        self::assertTrue($processDeposit);
    }

    public function testProcessDepositWithInvalidInput(): void
    {
        $_POST["amount"] = 'hi';
        $accountController = new AccountController($this->view, $this->repository, $this->entityManager, $this->validator);
        $processDeposit = $accountController->action();

        self::assertIsString($processDeposit);
    }

    public function testSessionUnset()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["loginStatus"] = true;
        $_SESSION["username"] = "testuser";

        $_POST["logout"] = true;

        $controller = new AccountController($this->view, $this->repository, $this->entityManager, $this->validator);
        $controller->action();

        $this->assertEquals(PHP_SESSION_NONE, session_status());
    }

    public function testGetCorrectAmount(): void
    {
        $accountController = new AccountController($this->view, $this->repository, $this->entityManager, $this->validator);
        $reformatString = $accountController->getCorrectAmount('10.000');

        self::assertSame(10000.0, $reformatString);
    }
}
