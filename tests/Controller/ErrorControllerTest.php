<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ErrorController;
use App\Core\Container;
use App\Core\View;

class ErrorControllerTest extends TestCase
{
    public function testAction()
    {
        $container = $this->createMock(Container::class);
        $view = $this->createMock(View::class);

        $container->expects($this->once())
            ->method('get')
            ->with(View::class)
            ->willReturn($view);

        $view->expects($this->once())
            ->method('addParameter')
            ->with('parameters', []);

        $view->expects($this->once())
            ->method('setTemplate')
            ->with('unknown.twig');

        $errorController = new ErrorController($container);
        $result = $errorController->action();
        $this->assertSame($view, $result);
    }
}
