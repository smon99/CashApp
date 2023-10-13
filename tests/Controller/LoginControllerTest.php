<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\LoginController;
use App\Core\Container;
use App\Core\View;
use App\Core\Redirect;
use App\Model\UserRepository;
use App\Model\UserDTO;

class LoginControllerTest extends TestCase
{
    private $container;
    private $view;
    private $redirect;
    private $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->createMock(Container::class);
        $this->view = $this->createMock(View::class);
        $this->redirect = $this->createMock(Redirect::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->container->method('get')
            ->willReturnMap([
                [View::class, $this->view],
                [Redirect::class, $this->redirect],
                [UserRepository::class, $this->userRepository],
            ]);
    }

    public function testActionWithValidCredentials(): void
    {
        $loginController = new LoginController($this->container);
        $loginController->action();

        $_POST['mail'] = 'Simon@Simon.de';
        $_POST['password'] = 'Simon123#';
        $_POST['login'] = true;

        $loginController->action();

        $this->assertTrue($_SESSION["loginStatus"]);
    }
}