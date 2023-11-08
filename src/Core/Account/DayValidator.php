<?php declare(strict_types=1);

namespace App\Core\Account;

use App\Model\AccountMapper;
use App\Model\AccountRepository;
use App\Model\SqlConnector;

class DayValidator implements AccountValidationInterface
{
    public function validate(float $amount, int $userID): void
    {
        $repository = new AccountRepository(new SqlConnector(), new AccountMapper());
        $dayBalance = $repository->calculateBalancePerDay($userID);

        $limit = $dayBalance + $amount;

        if ($limit > 500.0) {
            throw new AccountValidationException('Tägliches Einzahlungslimit von 500€ überschritten!');
        }
    }
}
