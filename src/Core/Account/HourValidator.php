<?php declare(strict_types=1);

namespace App\Core\Account;

use App\Model\AccountMapper;
use App\Model\AccountRepository;
use App\Model\SqlConnector;

class HourValidator implements AccountValidationInterface
{
    public function validate(float $amount, int $userID): void
    {
        $repository = new AccountRepository(new AccountMapper(), new SqlConnector());
        $hourBalance = $repository->calculateBalancePerHour($userID);

        $limit = $hourBalance + $amount;

        if ($limit > 100.0) {
            throw new AccountValidationException('Stündliches Einzahlungslimit von 100€ überschritten!');
        }
    }
}
