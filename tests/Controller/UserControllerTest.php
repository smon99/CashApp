<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\UserController;
use App\Core\Container;
use App\Core\View;
use App\Core\Redirect;
use App\Model\UserEntityManager;

class UserControllerTest extends TestCase
{
    private Container|\PHPUnit\Framework\MockObject\MockObject $container;
    private View|\PHPUnit\Framework\MockObject\MockObject $view;
    private Redirect|\PHPUnit\Framework\MockObject\MockObject $redirect;
    private UserEntityManager|\PHPUnit\Framework\MockObject\MockObject $userEntityManager;
    private UserController $controller;

    protected function setUp(): void
    {
        $this->container = $this->createMock(Container::class);
        $this->view = $this->createMock(View::class);
        $this->redirect = $this->createMock(Redirect::class);
        $this->userEntityManager = $this->createMock(UserEntityManager::class);

        $this->container->method('get')
            ->willReturnMap([
                [View::class, $this->view],
                [Redirect::class, $this->redirect],
                [UserEntityManager::class, $this->userEntityManager],
            ]);

        $this->controller = new UserController($this->container);
    }

    public function testRegistrationValid(): void
    {
        $_POST["register"] = true;
        $_POST["username"] = "Tester";
        $_POST["mail"] = "Tester@Tester.de";
        $_POST["password"] = "Tester123#";

        $this->userEntityManager->expects($this->once())
            ->method('save');

        $this->redirect->expects($this->once())
            ->method('redirectTo')
            ->with('http://0.0.0.0:8000/?page=login');

        $response = $this->controller->action();

        $this->assertInstanceOf(View::class, $response);
    }

    public function testRegistrationInvalid(): void
    {
        $_POST["register"] = "Register";
        $_POST["username"] = "testuser";
        $_POST["mail"] = "test@example.com";
        $_POST["password"] = "invalid";

        ob_start();
        $response = $this->controller->action();
        $output = ob_get_clean();

        $this->assertInstanceOf(View::class, $response);
    }
}
