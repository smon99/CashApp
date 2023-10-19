<?php declare(strict_types=1);

namespace Test\Controller;

use App\Controller\AccountController;
use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\RedirectRecordings;
use App\Core\View;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertInstanceOf;

class AccountControllerTest extends TestCase
{
    public RedirectRecordings $redirectRecordings;

    protected function setUp(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();
        $provider->provide($container);

        $this->redirectRecordings = new RedirectRecordings();

        $this->container = $container;
        $this->controller = new AccountController($this->container);

        session_start();
        $_SESSION["userID"] = 4;
        $_SESSION["loginStatus"] = true;
        $_SESSION["username"] = "Simon";

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testAction(): void
    {
        $_POST["amount"] = '1';

        assertContains("Die Transaktion wurde erfolgreich gespeichert!", $this->controller->action()->getParameters());
    }

    public function testActionException(): void
    {
        $_POST["amount"] = '500';
        $this->controller->action();

        $viewParams = $this->controller->action()->getParameters();

        self::assertContains("Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!", $viewParams);
    }

    public function testActionNoSession(): void
    {
        unset($_SESSION["loginStatus"]);

        $this->controller->action();
        $url = $this->controller->redirect->redirectRecordings->recordedUrl[0];

        self::assertSame($url, 'http://0.0.0.0:8000/?page=login');
    }

    public function testActionLogOut(): void
    {
        $_POST["logout"] = true;
        $this->controller->action();
        $url = $this->controller->redirect->redirectRecordings->recordedUrl[0];

        self::assertSame($url, 'http://0.0.0.0:8000/?page=login');
    }

    protected function tearDown(): void
    {
        session_destroy();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

}