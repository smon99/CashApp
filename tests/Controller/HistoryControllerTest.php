<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\HistoryController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\View;
use PHPUnit\Framework\TestCase;

class HistoryControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();

        $this->controller = new HistoryController($container);
    }

    public function testAction(): void
    {
        session_start();
        $_SESSION["loginStatus"] = true;
        $_SESSION["userID"] = 1;

        $history = $this->controller->action();
        $header = $this->redirectRecordings->recordedUrl;

        self::assertInstanceOf(View::class, $history);
        self::assertEmpty($header);
    }

    public function testActionNoSession(): void
    {
        session_start();
        $_SESSION["loginStatus"] = true;
        $_SESSION["userID"] = 1;
        unset($_SESSION["loginStatus"]);

        $this->controller->action();
        $header = $this->redirectRecordings->recordedUrl;

        self::assertEmpty($header);
    }

    protected function tearDown(): void
    {
        session_destroy();
    }

}