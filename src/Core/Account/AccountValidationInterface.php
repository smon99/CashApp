<?php declare(strict_types=1);

namespace App\Core\Account;

interface AccountValidationInterface
{
    /**
     * @param float $amount
     *
     * @throws AccountValidationException
     *
     * @return void
     */
    public function validate(float $amount): void;
}
