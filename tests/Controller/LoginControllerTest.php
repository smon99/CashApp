<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\LoginController;
use App\Core\Container;
use App\Core\View;
use App\Core\Redirect;
use App\Model\UserRepository;
use App\Model\UserDTO;
use function PHPUnit\Framework\assertStringContainsString;

class LoginControllerTest extends TestCase
{
    private Container|\PHPUnit\Framework\MockObject\MockObject $container;
    private View|\PHPUnit\Framework\MockObject\MockObject $view;
    private Redirect|\PHPUnit\Framework\MockObject\MockObject $redirect;
    private UserRepository|\PHPUnit\Framework\MockObject\MockObject $userRepository;
    private LoginController $controller;

    protected function setUp(): void
    {
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

        $this->controller = new LoginController($this->container);
    }

    public function testLoginWithValidCredentials(): void
    {
        $_POST['login'] = true;
        $_POST['mail'] = 'TestUser@TestUser.de';
        $_POST['password'] = 'valid_password';

        $userDTO = new UserDTO();
        $userDTO->username = 'TestUser';
        $userDTO->password = password_hash('valid_password', PASSWORD_DEFAULT);

        $this->userRepository->expects($this->once())
            ->method('findByMail')
            ->with('TestUser@TestUser.de')
            ->willReturn($userDTO);

        $this->redirect->expects($this->once())
            ->method('redirectTo')
            ->with('http://0.0.0.0:8000/?page=deposit');

        ob_start();
        $response = $this->controller->action();
        $output = ob_get_clean();

        $this->assertInstanceOf(View::class, $response);
        $this->assertStringContainsString('Logged in as TestUser', $output);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $_POST['login'] = null;

        $response = $this->controller->action();

        self::assertInstanceOf(View::class, $response);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

}