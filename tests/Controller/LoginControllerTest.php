<?php declare(strict_types=1);

namespace Test\Controller;

use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\Session;
use App\Model\SqlConnector;
use App\Model\UserDTO;
use App\Model\UserEntityManager;
use App\Model\UserMapper;
use PHPUnit\Framework\TestCase;
use App\Controller\LoginController;
use App\Core\Container;

class LoginControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;
    private Session $session;
    private UserDTO $userDTO;
    private UserEntityManager $userEntityManager;
    private SqlConnector $sqlConnector;
    private UserMapper $userMapper;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();
        $this->session = new Session();

        $this->container = $container;
        $this->controller = new LoginController($this->container);

        $this->userDTO = new UserDTO();

        $this->userDTO->username = 'Simon';
        $this->userDTO->email = 'Simon@Simon.de';
        $this->userDTO->password = '$2y$10$rqTcf57sIEVAZsertDU7P.8O3kObwxc17jL6Cec.6oMcX/VWdFX0i';

        $this->sqlConnector = new SqlConnector();
        $this->userMapper = new  UserMapper();
        $this->userEntityManager = new UserEntityManager($this->sqlConnector, $this->userMapper);

        $this->userEntityManager->save($this->userDTO);

        session_start();
    }

    protected function tearDown(): void
    {
        unset($_POST['mail'], $_POST['password'], $_POST['login']);
        $this->sqlConnector->execute("DELETE FROM Users;", []);
        $this->session->logout();
    }

    public function testAction(): void
    {
        $_POST['mail'] = 'Simon@Simon.de';
        $_POST['password'] = 'Simon123#';
        $_POST['login'] = true;

        $this->controller->action();
        $viewParams[] = $this->controller->action()->getParameters();

        self::assertContains('Login Page', $viewParams[0]);
        self::assertSame('Simon', $this->session->getUserName());
    }

    public function testActionViewRedirect(): void
    {
        $_POST['mail'] = 'Simon@Simon.de';
        $_POST['password'] = 'Simon123#';
        $_POST['login'] = true;

        $this->controller->action();
        $url[] = $this->controller->redirect->redirectRecordings->recordedUrl[0];

        self::assertSame('http://0.0.0.0:8000/?page=feature', $url[0]);
    }

    public function testActionViewPath(): void
    {
        self::assertSame('login.twig', $this->controller->action()->getTpl());
    }
}