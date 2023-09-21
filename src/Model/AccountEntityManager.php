<?php declare(strict_types=1);

namespace App\Model;

class AccountEntityManager
{
    private string $path;
    private string $jsonString;

    public function __construct(?string $path = AccountRepository::ACCOUNT_DEFAULT_PATH)
    {
        $this->path = $path;
        $this->jsonString = file_get_contents($path);
    }

    public function saveDeposit(AccountDTO $deposit): void
    {
        $accountMapper = new AccountMapper();

        $accountDTOList = $accountMapper->jsonToDTO($this->jsonString);

        $accountDTOList[] = $deposit;

        $this->jsonString = $accountMapper->jsonFromDTO($accountDTOList);
        file_put_contents($this->path, $this->jsonString);
    }
}
