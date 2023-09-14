<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\UserController;
use App\Core\Redirect;
use App\Core\View;
use App\Core\ViewInterface;

class UserControllerTest extends TestCase
{
    private ViewInterface $view;
    private Redirect $redirect;

    protected function setUp(): void
    {
        parent::setUp();

        $this->view = new View(__DIR__ . '/../../src/View');
        $this->redirect = new Redirect('http://0.0.0.0:8000/?input=login');
    }

    public function testRegistrationValid(): void
    {
        $redirect = $this->createMock(Redirect::class);
        $redirect
            ->expects(self::once())
            ->method('redirectTo')
            ->with('http://0.0.0.0:8000/?input=login');

        $userController = new UserController($this->view, $redirect);

        $_POST['username'] = 'Test';
        $_POST['mail'] = 'Test@Test.de';
        $_POST['password'] = 'Test123#';
        $_POST['register'] = true;

        $userController->registration();

    }

    public function testValidatePasswordTrue(): void
    {
        $userController = new UserController($this->view, $this->redirect);

        $correctExamplePassword = 'Password123#';

        self::assertTrue($userController->validatePassword($correctExamplePassword));
    }

    public function testValidatePasswordFalse(): void
    {
        $passwordNoNumber = 'Password#';
        $passwordNoUppercase = 'password123#';
        $passwordNoLowercase = 'PASSWORD123#';
        $passwordNoSpecialchar = 'Password123';

        $userController = new UserController($this->view, $this->redirect);

        self::assertFalse($userController->validatePassword($passwordNoNumber));
        self::assertFalse($userController->validatePassword($passwordNoUppercase));
        self::assertFalse($userController->validatePassword($passwordNoLowercase));
        self::assertFalse($userController->validatePassword($passwordNoSpecialchar));
    }
}