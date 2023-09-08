<?php declare(strict_types=1);

namespace App\Model;

class AccountEntityManager
{
    private string $path;

    public function __construct(?string $path = null)
    {
        if ($path === null) {
            $path = AccountRepository::ACCOUNT_DEFAULT_PATH;
        }

        $this->path = $path;
    }

    public function saveDeposit($deposit): void
    {
        if (!file_exists($this->path)) {
            $firstDeposit = [$deposit];
            $saveDeposit = $firstDeposit;
            file_put_contents($this->path, json_encode([]));
        } else {
            $oldDeposit = json_decode(file_get_contents($this->path));
            $oldDeposit[] = $deposit;
            $saveDeposit = $oldDeposit;
        }
        $deposit_data = json_encode($saveDeposit, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents($this->path, $deposit_data, LOCK_EX);
    }
}