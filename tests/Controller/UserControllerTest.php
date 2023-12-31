<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\UserController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\View;
use App\Model\SqlConnector;
use App\Model\UserMapper;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class UserControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;
    private UserRepository $userRepository;
    private SqlConnector $sqlConnector;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->sqlConnector = new SqlConnector();
        $userMapper = new UserMapper();

        $this->redirectRecordings = new RedirectRecordings();
        $this->userRepository = new UserRepository($this->sqlConnector, $userMapper);

        $this->container = $container;
        $this->controller = new UserController($this->container);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->execute("DELETE FROM Users;", []);
        unset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['register']);
    }

    public function testActionInstance(): void
    {
        self::assertInstanceOf(View::class, $this->controller->action());
    }

    public function testActionRegistration(): void
    {
        $_POST['username'] = 'Tester';
        $_POST['email'] = 'Tester@Tester.de';
        $_POST['password'] = 'Tester123#';

        $_POST['register'] = true;

        $parameters = $key['parameters'] = $this->controller->action()->getParameters();
        $url = $this->controller->redirect->redirectRecordings->recordedUrl[0];

        $registeredUser = $this->userRepository->findByMail('Tester@Tester.de');

        self::assertContains('Tester', $parameters);
        self::assertContains('Tester@Tester.de', $parameters);
        self::assertContains('Tester123#', $parameters);
        self::assertSame('Tester', $registeredUser->username);
        self::assertSame('http://0.0.0.0:8000/?page=login', $url);
    }

    public function testActionValidationException(): void
    {
        $_POST['username'] = 'Tester';
        $_POST['email'] = 'TesterTester.de';
        $_POST['password'] = 'Tester123#';

        $_POST['register'] = true;

        self::assertContains('Bitte gültige eMail eingeben! ', $this->controller->action()->getParameters());
    }

    public function testActionTemplatePath(): void
    {
        self::assertSame('user.twig', $this->controller->action()->getTpl());
    }
}