<?php declare(strict_types=1);

namespace Test\Core;

use App\Core\Container;
use App\Core\DependencyProvider;
use App\Core\Redirect;
use App\Core\User\EmptyFieldValidator;
use App\Core\UserValidation;
use App\Core\View;
use App\Model\AccountEntityManager;
use App\Model\AccountRepository;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class DependencyProviderTest extends TestCase
{
    public function testProvide(): void
    {
        $container = new Container();
        $provider = new DependencyProvider();

        $provider->provide($container);

        self::assertSame('/home/simondewendt/PhpstormProjects/CashApp/src/Core/../View', $container->get(View::class)->templatePath);

        $this->assertInstanceOf(View::class, $container->get(View::class));
        $this->assertInstanceOf(Redirect::class, $container->get(Redirect::class));

        $this->assertInstanceOf(AccountRepository::class, $container->get(AccountRepository::class));
        $this->assertInstanceOf(UserRepository::class, $container->get(UserRepository::class));

        $this->assertInstanceOf(AccountEntityManager::class, $container->get(AccountEntityManager::class));
        $this->assertInstanceOf(UserEntityManager::class, $container->get(UserEntityManager::class));

        $this->assertInstanceOf(UserValidation::class, $container->get(UserValidation::class));
    }
}