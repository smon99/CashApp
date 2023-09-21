<?php declare(strict_types=1);

namespace App\Core\Account;

interface AccountValidationInterface
{
    public function validate(float $amount);
}