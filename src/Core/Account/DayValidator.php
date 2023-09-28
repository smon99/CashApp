<?php declare(strict_types=1);

namespace App\Core\Account;

use App\Model\AccountMapper;
use App\Model\AccountRepository;

class DayValidator implements AccountValidationInterface
{
    public function validate(float $amount): void
    {
        $repository = new AccountRepository(new AccountMapper());
        $dayBalance = $repository->calculateBalancePerDay();

        $limit = $dayBalance + $amount;

        if ($limit > 500.0) {
            throw new AccountValidationException('Tägliches Einzahlungslimit von 500€ überschritten!');
        }
    }
}
