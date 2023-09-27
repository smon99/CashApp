<?php declare(strict_types=1);

namespace Test\Controller;

use App\Model\UserDTO;
use App\Model\UserMapper;
use PHPUnit\Framework\TestCase;
use App\Controller\LoginController;
use App\Core\ViewInterface;
use App\Core\Redirect;
use App\Model\UserRepository;

class LoginControllerTest extends TestCase
{
    private $view;
    private $redirect;
    private $userRepository;
    private $loginController;

    protected function setUp(): void
    {
        //$path = __DIR__ . '/../../tests/Model/user.json';
        $this->view = $this->createMock(ViewInterface::class);
        $this->redirect = $this->createMock(Redirect::class);
        $this->userRepository = new UserRepository(new UserMapper());

        $this->loginController = new LoginController($this->view, $this->redirect, $this->userRepository);
    }

    public function testFormInputWithInvalidCredentials(): void
    {
        unset($_POST['login']);
        $loginController = $this->loginController;
        $formInput = $loginController->formInput();

        self::assertNull($formInput);
    }

    public function testUserLoginWithValidCredentials(): void
    {
        $_POST['login'] = true;
        $_POST['mail'] = 'Test@Test.de';
        $_POST['password'] = 'Test123#';

        $LoginController = $this->loginController;
        $userLogin = $LoginController->userLogin();

        self::assertTrue($userLogin);
    }

    public function testUserLoginWithInvalidCredentials(): void
    {
        $_POST['login'] = true;
        $_POST['mail'] = 'Test@Test.de';
        $_POST['password'] = 'invalidPassword';

        $LoginController = $this->loginController;
        $userLogin = $LoginController->userLogin();

        self::assertFalse($userLogin);
        self::assertFalse($_SESSION["loginStatus"]);
    }
}
