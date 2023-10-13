<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\LoginController;
use App\Core\Container;
use App\Core\View;
use App\Core\Redirect;
use App\Model\UserRepository;

class LoginControllerTest extends TestCase
{
    public function testActionWithValidCredentials(): void
    {
        $container = $this->createMock(Container::class);
        $view = $this->createMock(View::class);
        $redirect = $this->createMock(Redirect::class);
        $userRepository = $this->createMock(UserRepository::class);

        $container->method('get')
            ->willReturnOnConsecutiveCalls($view, $userRepository, $redirect);

        $userDTO = new \App\Model\UserDTO();
        $userDTO->password = password_hash('valid_password', PASSWORD_BCRYPT);

        $userRepository->expects($this->once())
            ->method('findByMail')
            ->with('valid_email@example.com')
            ->willReturn($userDTO);

        $redirect->expects($this->once())
            ->method('redirectTo')
            ->with('http://0.0.0.0:8000/?page=feature');

        $loginController = new LoginController($container);

        $_POST['login'] = true;
        $_POST['mail'] = 'valid_email@example.com';
        $_POST['password'] = 'valid_password';

        $result = $loginController->action();

        $this->assertSame($view, $result);
    }
}