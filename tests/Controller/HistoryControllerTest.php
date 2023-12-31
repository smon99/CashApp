<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\HistoryController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\Session;
use App\Core\View;
use App\Model\SqlConnector;
use App\Model\UserDTO;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEmpty;

class HistoryControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;
    private UserDTO $userDTO;
    private Session $session;
    private HistoryController $controller;
    private SqlConnector $connector;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();
        $this->session = new Session();
        $this->connector = new SqlConnector();

        $this->controller = new HistoryController($container);

        $this->userDTO = new UserDTO();
        $this->userDTO->password = '$2y$10$rqTcf57sIEVAZsertDU7P.8O3kObwxc17jL6Cec.6oMcX/VWdFX0i';
        $this->userDTO->username = 'Simon';
        $this->userDTO->email = 'Simon@Simon.de';
        $this->userDTO->userID = 4;

        session_start();
        $this->session->loginUser($this->userDTO, 'Simon123#');
    }

    protected function tearDown(): void
    {
        $this->connector->execute("DELETE FROM Transactions;", []);
        unset($_SESSION["loginStatus"], $_SESSION["userID"]);
        session_destroy();
    }

    public function testAction(): void
    {
        $history = $this->controller->action();
        $header = $this->redirectRecordings->recordedUrl;

        self::assertInstanceOf(View::class, $history);
        self::assertEmpty($header);
    }

    public function testActionNoSession(): void
    {
        $this->session->logout();

        $this->controller->action();
        $header[] = $this->controller->redirect->redirectRecordings->recordedUrl;

        self::assertSame('http://0.0.0.0:8000/?page=login', $header[0][0]);
    }

    public function testActionViewParameters(): void
    {
        $viewParams[] = $this->controller->action()->getParameters();
        $assertion = $viewParams[0]['transactions'];

        assertEmpty($assertion);
        self::assertSame('history.twig', $this->controller->action()->getTpl());
    }
}