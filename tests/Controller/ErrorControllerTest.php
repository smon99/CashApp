<?php declare(strict_types=1);

namespace Test\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ErrorController;
use App\Core\Container;
use App\Core\View;

class ErrorControllerTest extends TestCase
{
    private Container|\PHPUnit\Framework\MockObject\MockObject $container;
    private View|\PHPUnit\Framework\MockObject\MockObject $view;
    private ErrorController $controller;

    protected function setUp(): void
    {
        $this->container = $this->createMock(Container::class);
        $this->view = $this->createMock(View::class);

        $this->container->method('get')
            ->willReturnMap([
                [View::class, $this->view],
            ]);

        $this->controller = new ErrorController($this->container);
    }

    public function testAction(): void
    {
        $this->view->expects($this->once())
            ->method('setTemplate')
            ->with('unknown.twig');

        $response = $this->controller->action();

        $this->assertInstanceOf(View::class, $response);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
