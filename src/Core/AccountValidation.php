<?php declare(strict_types=1);

namespace App\Core;

use App\Core\Account\AccountValidationInterface;
use App\Core\Account\AccountValidationException;

class AccountValidation
{
    private array $validationCollection;

    public function __construct(AccountValidationInterface ...$validations)
    {
        $this->validationCollection = $validations;
    }

    public function collectErrors(float $amount): void
    {
        $errors = [];

        if (!is_numeric($amount)) {
            throw new AccountValidationException('Bitte einen Betrag eingeben!');
        }

        foreach ($this->validationCollection as $validator) {
            try {
                $validator->validate($amount);
            } catch (AccountValidationException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            throw new AccountValidationException(implode(' ', $errors));
        }
    }
}
