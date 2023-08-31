<?php declare(strict_types=1);

namespace Model;

class AccountEntityManager
{
    public function saveDeposit($deposit): void
    {
        if (!file_exists(__DIR__ . '/../Model/account.json')) {
            $firstDeposit = [$deposit];
            $saveDeposit = $firstDeposit;
            file_put_contents(__DIR__ . '/../Model/account.json', json_encode([]));
        } else {
            $oldDeposit = json_decode(file_get_contents(__DIR__ . '/../Model/account.json'));
            $oldDeposit[] = $deposit;
            $saveDeposit = $oldDeposit;
        }
        $deposit_data = json_encode($saveDeposit, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents(__DIR__ . '/../Model/account.json', $deposit_data, LOCK_EX);
    }
}