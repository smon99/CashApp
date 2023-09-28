<?php declare(strict_types=1);

namespace App\Core\Account;

class DayValidator implements AccountValidationInterface
{
    public function validate(float $amount): void
    {
        $accountData = json_decode(file_get_contents(__DIR__ . '/../../Model/account.json'), true);

        $date = date('Y-m-d');

        foreach ($accountData as $transactionSet) {
            if ($date === $transactionSet["date"]) {
                $amount += $transactionSet["amount"];
            }
        }

        if ($amount >= 500) {
            throw new AccountValidationException('Tägliches Einzahlungslimit von 500€ überschritten!');
        }
    }
}
