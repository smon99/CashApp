<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\FeatureController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\View;
use PHPUnit\Framework\TestCase;

class FeatureControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->container = $container;
        $this->controller = new FeatureController($this->container);
    }

    public function testAction(): void
    {
        $feature = $this->controller->action();

        self::assertInstanceOf(View::class, $feature);
    }

    public function testActionLoginRedirect(): void
    {

    }
}