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

class DependencyProvider
{
    public function provide(Container $container): void
    {
        $container->set(View::class, new View(__DIR__ . '/../View'));
        $container->set(Redirect::class, new Redirect());

        $container->set(AccountRepository::class, new AccountRepository(new AccountMapper()));
        $container->set(UserRepository::class, new UserRepository(new UserMapper(), new SqlConnector()));

        $container->set(AccountEntityManager::class, new AccountEntityManager());
        $container->set(UserEntityManager::class, new UserEntityManager(new SqlConnector(), new UserMapper()));

        $container->set(AccountValidation::class, new AccountValidation(new SingleValidator(), new DayValidator(), new HourValidator()));
        $container->set(UserValidation::class, new UserValidation(new EmptyFieldValidator(), new EMailValidator(), new PasswordValidator(), new UserDuplicationValidator()));
    }
}