<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\TransactionController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\Session;
use App\Model\AccountDTO;
use App\Model\AccountEntityManager;
use App\Model\AccountMapper;
use App\Model\AccountRepository;
use App\Model\SqlConnector;
use App\Model\UserDTO;
use App\Model\UserEntityManager;
use App\Model\UserMapper;
use PHPUnit\Framework\TestCase;

class TransactionControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;
    private Session $session;
    private UserDTO $userDTO;
    private AccountDTO $accountDTO;
    private AccountRepository $accountRepository;
    private AccountEntityManager $accountEntityManager;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();
        $this->session = new Session();

        $this->container = $container;
        $this->controller = new TransactionController($this->container);
        $this->accountRepository = new AccountRepository(new AccountMapper(), new SqlConnector());
        $userEntityManager = new UserEntityManager(new SqlConnector(), new UserMapper());
        $this->accountEntityManager = new AccountEntityManager(new SqlConnector(), new AccountMapper());

        $this->userDTO = new UserDTO();
        $this->userDTO->password = '$2y$10$rqTcf57sIEVAZsertDU7P.8O3kObwxc17jL6Cec.6oMcX/VWdFX0i';
        $this->userDTO->username = 'Simon';
        $this->userDTO->email = 'Simon@Simon.de';

        $userReceiverDTO = new UserDTO();
        $userReceiverDTO->password = '$2y$10$rqTcf57sIEVAZsertDU7P.8O3kObwxc17jL6Cec.6oMcX/VWdFX0i';
        $userReceiverDTO->username = 'Nico';
        $userReceiverDTO->email = 'Nico@Nico.de';

        $this->accountDTO = new AccountDTO();
        $this->accountDTO->value = 10.0;
        $this->accountDTO->userID = $this->userDTO->userID;
        $this->accountDTO->transactionDate = date('Y-m-d');
        $this->accountDTO->transactionTime = date('H:i:s');
        $this->accountDTO->purpose = 'testing';

        $userEntityManager->save($this->userDTO);
        $userEntityManager->save($userReceiverDTO);

        session_start();
    }

    public function testAction(): void
    {
        $this->session->loginUser($this->userDTO, 'Simon123#');
        self::assertContains("Simon", $this->controller->action()->getParameters());
        $this->session->logout();
    }

    public function testActionNoSession(): void
    {
        $this->session->logout();

        $this->controller->action();
        $url = $this->controller->redirect->redirectRecordings->recordedUrl[0];

        self::assertSame($url, 'http://0.0.0.0:8000/?page=login');
    }

    public function testActionLogOut(): void
    {
        $this->session->loginUser($this->userDTO, 'Simon123#');
        $_POST["logout"] = true;
        $this->controller->action();

        self::assertEmpty($_SESSION);

        $this->session->logout();
    }

    public function testActionTransaction(): void
    {
        $this->accountEntityManager->saveDeposit($this->accountDTO);
        $this->session->loginUser($this->userDTO, 'Simon123#');

        $this->controller->action();

        $_POST["amount"] = "1";
        $_POST["receiver"] = 'Nico@Nico.de';
        $_POST["transfer"] = true;

        $this->controller->action();

        $transactions[] = $this->accountRepository->fetchAllTransactions();
        $entry = $transactions[0][2];

        self::assertSame(1.0, $entry->value);
        $this->session->logout();
    }

    public function testActionException(): void
    {
        unset($_POST["amount"]);
        $this->session->loginUser($this->userDTO, 'Simon123#');
        $_POST["amount"] = '500';
        $_POST["receiver"] = 'Nico@Nico.de';
        $_POST["transfer"] = true;

        $viewParams = $this->controller->action()->getParameters();

        self::assertContains("Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!", $viewParams);
        $this->session->logout();
    }

    protected function tearDown(): void
    {
        $connector = new SqlConnector();
        $connector->executeDeleteQuery("DELETE FROM Transactions;", []);
        $connector->executeDeleteQuery("DELETE FROM Users;", []);
        $connector->disconnect();
        $this->session->logout();

        unset($_POST["logout"], $_POST["receiver"], $_POST["amount"], $_POST["transfer"], $this->userDTO, $this->redirectRecordings, $this->session);
        session_destroy();
    }
}