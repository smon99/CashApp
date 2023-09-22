<?php declare(strict_types=1);

namespace App\Core;

use App\Core\Account\AccountValidationInterface;

class AccountValidation
{
    private array $validationCollection;

    public function __construct(
        AccountValidationInterface ...$validations
    )
    {
        $this->validationCollection = $validations;
    }

    public function collectErrors($amount): string|bool
    {
        if (!is_numeric($amount)) {
            return 'Bitte einen Betrag eingeben!';
        }

        foreach ($this->validationCollection as $validator) {
            if ($validator->validate($amount) !== true) {
                return $validator->validate($amount);
            }
        }
        return true;
    }
}