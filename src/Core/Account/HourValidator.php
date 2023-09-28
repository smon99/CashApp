<?php declare(strict_types=1);

namespace App\Core\Account;

use App\Model\AccountMapper;
use App\Model\AccountRepository;

class HourValidator implements AccountValidationInterface
{
    public function validate(float $amount): void
    {
        $repository = new AccountRepository(new AccountMapper());
        $hourBalance = $repository->calculateBalancePerHour();

        $limit = $hourBalance + $amount;

        if ($limit > 100.0) {
            throw new AccountValidationException('Stündliches Einzahlungslimit von 100€ überschritten!');
        }
    }
}
