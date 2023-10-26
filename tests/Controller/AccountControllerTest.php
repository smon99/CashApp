<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\AccountController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\Session;
use App\Model\SqlConnector;
use App\Model\UserDTO;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertContains;

class AccountControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;
    private Session $session;
    private UserDTO $userDTO;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();
        $this->session = new Session();

        $this->container = $container;
        $this->controller = new AccountController($this->container);

        $this->userDTO = new UserDTO();
        $this->userDTO->password = '$2y$10$rqTcf57sIEVAZsertDU7P.8O3kObwxc17jL6Cec.6oMcX/VWdFX0i';
        $this->userDTO->username = 'Simon';
        $this->userDTO->email = 'Simon@Simon.de';
        $this->userDTO->userID = 4;

        session_start();
    }

    public function testAction(): void
    {
        $this->session->loginUser($this->userDTO, 'Simon123#');
        $_POST["amount"] = '1';

        assertContains("Die Transaktion wurde erfolgreich gespeichert!", $this->controller->action()->getParameters());
        $this->session->logout();
    }

    public function testActionException(): void
    {
        unset($_POST["amount"]);
        $this->session->loginUser($this->userDTO, 'Simon123#');
        $_POST["amount"] = '500';

        $viewParams = $this->controller->action()->getParameters();

        self::assertContains("Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!", $viewParams);
        $this->session->logout();
    }

    public function testActionNoSession(): void
    {
        unset($_POST["amount"]);
        $this->session->loginUser($this->userDTO, 'Simon123#');
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
        $url = $this->controller->redirect->redirectRecordings->recordedUrl[0];

        self::assertSame($url, 'http://0.0.0.0:8000/?page=login');
        $this->session->logout();
    }

    protected function tearDown(): void
    {
        $connector = new SqlConnector();
        $connector->execute("DELETE FROM Transactions;", []);
        $connector->disconnect();
        $this->session->logout();
        unset($_POST["amount"], $_POST["logout"], $this->userDTO, $this->redirectRecordings, $this->session);
        session_destroy();
    }
}