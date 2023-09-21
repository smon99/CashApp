<?php declare(strict_types=1);

namespace App\Core\Account;

class SingleValidator implements AccountValidationInterface
{
    public function validate(float $amount): string|bool
    {
        if ($amount >= 0.01 && $amount <= 50) {
            return true;
        }
        return 'Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!';
    }
}