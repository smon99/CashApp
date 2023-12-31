<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\FeatureController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\Session;
use App\Core\View;
use App\Model\UserDTO;
use PHPUnit\Framework\TestCase;

class FeatureControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;
    private UserDTO $userDTO;
    private Session $session;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();
        $this->session = new Session();

        $this->container = $container;
        $this->controller = new FeatureController($this->container);

        $this->userDTO = new UserDTO();
        $this->userDTO->password = '$2y$10$rqTcf57sIEVAZsertDU7P.8O3kObwxc17jL6Cec.6oMcX/VWdFX0i';
        $this->userDTO->username = 'Simon';
        $this->userDTO->email = 'Simon@Simon.de';
        $this->userDTO->userID = 4;

        session_start();
    }

    protected function tearDown(): void
    {
        unset($_SESSION["username"]);
        session_destroy();
    }

    public function testAction(): void
    {
        $this->session->loginUser($this->userDTO, 'Simon123#');
        $feature = $this->controller->action();
        $header = $this->redirectRecordings->recordedUrl;

        self::assertInstanceOf(View::class, $feature);
        self::assertEmpty($header);
    }

    public function testActionNoSession(): void
    {
        $this->session->logout();
        $this->controller->action();

        $url = $this->controller->redirect->redirectRecordings->recordedUrl;
        self::assertSame('http://0.0.0.0:8000/?page=login', $url[0]);
    }

    public function testActionView(): void
    {
        $this->session->loginUser($this->userDTO, 'Simon123#');

        self::assertContains('Simon', $this->controller->action()->getParameters());
        self::assertSame('feature.twig', $this->controller->action()->getTpl());
    }
}