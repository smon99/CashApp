<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\UserController;
use App\Core\Redirect;
use App\Model\UserEntityManager;
use App\Core\ViewInterface;
use App\Model\UserDTO;

class UserControllerTest extends TestCase
{
    private $viewMock;
    private $redirectMock;
    private $userEntityManagerMock;
    private $userController;

    protected function setUp(): void
    {
        $this->viewMock = $this->createMock(ViewInterface::class);
        $this->redirectMock = $this->createMock(Redirect::class);
        $this->userEntityManagerMock = $this->createMock(UserEntityManager::class);

        $this->userController = new UserController(
            $this->viewMock,
            $this->redirectMock,
            $this->userEntityManagerMock
        );
    }

    public function testRegistrationWithValidData(): void
    {
        $_POST['register'] = true;
        $_POST['username'] = 'TestUser';
        $_POST['mail'] = 'TestUser@TestUser.de';
        $_POST['password'] = 'Passwor123#';

        $this->userController->action();


    }

}
