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
        $firstError = null;

        foreach ($this->validationCollection as $validator) {
            try {
                $validator->validate($amount);
            } catch (AccountValidationException $e) {
                if ($firstError === null) {
                    $firstError = $e;
                }
            }
        }

        if ($firstError !== null) {
            throw $firstError;
        }
    }
}
