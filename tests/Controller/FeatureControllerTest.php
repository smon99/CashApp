<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\FeatureController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\View;
use PHPUnit\Framework\TestCase;

class FeatureControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();

        $this->container = $container;
        $this->controller = new FeatureController($this->container);
    }

    protected function tearDown(): void
    {
        unset($_SESSION["username"]);
        session_destroy();
    }

    public function testAction(): void
    {
        session_start();
        $_SESSION["username"] = '';

        $feature = $this->controller->action();
        $header = $this->redirectRecordings->recordedUrl;

        self::assertInstanceOf(View::class, $feature);
        self::assertEmpty($header);
    }
}