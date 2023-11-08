<?php declare(strict_types=1);

namespace App\Core;

use App\Core\Account\DayValidator;
use App\Core\Account\HourValidator;
use App\Core\Account\SingleValidator;
use App\Core\User\EMailValidator;
use App\Core\User\EmptyFieldValidator;
use App\Core\User\PasswordValidator;
use App\Core\User\UserDuplicationValidator;
use App\Model\AccountEntityManager;
use App\Model\AccountMapper;
use App\Model\AccountRepository;
use App\Model\SqlConnector;
use App\Model\UserEntityManager;
use App\Model\UserMapper;
use App\Model\UserRepository;
use function PHPUnit\Framework\never;

class DependencyProvider
{
    public function provide(Container $container): void
    {
        $container->set(View::class, new View(__DIR__ . '/../View'));
        $container->set(Redirect::class, new Redirect(new RedirectRecordings()));
        $container->set(Session::class, new Session());
        $container->set(InputTransformer::class, new InputTransformer());
        $container->set(SqlConnector::class, new SqlConnector());

        $container->set(UserMapper::class, new UserMapper());
        $container->set(AccountMapper::class, new AccountMapper());

        $container->set(AccountRepository::class, new AccountRepository($container->get(SqlConnector::class), $container->get(AccountMapper::class)));
        $container->set(UserRepository::class, new UserRepository($container->get(SqlConnector::class), $container->get(UserMapper::class)));

        $container->set(AccountEntityManager::class, new AccountEntityManager($container->get(SqlConnector::class), $container->get(AccountMapper::class)));
        $container->set(UserEntityManager::class, new UserEntityManager($container->get(SqlConnector::class), $container->get(UserMapper::class)));

        $container->set(AccountValidation::class, new AccountValidation(new SingleValidator(), new DayValidator(), new HourValidator()));
        $container->set(UserValidation::class, new UserValidation(new EmptyFieldValidator(), new EMailValidator(), new PasswordValidator(), new UserDuplicationValidator()));
    }
}