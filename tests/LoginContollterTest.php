<?php

namespace Test;

use App\Controller\LoginController;
use App\Core\Redirect;
use App\Core\View;
use App\Core\ViewInterface;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    private ViewInterface $view;
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->view = new View(__DIR__ . '/../../src/View');
    }
    
    public function testSuccess(): void
    {
        $redirect = $this->createMock(Redirect::class);
        $redirect
            ->expects(self::once())
            ->method('redirectTo')
            ->with('http://0.0.0.0:8000/?input=deposit');
        
        $controller = new LoginController($this->view, $redirect);
        
        $_POST['email'] = 'Simon@Simon.de';
        $_POST['password'] = 'Simon123#';
        $_POST['login'] = true;
        
        $controller->userLogin();
    }
}
