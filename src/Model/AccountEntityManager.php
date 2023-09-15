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

    public function saveDeposit(AccountDTO $deposit): void
    {
        $accountMapper = new AccountMapper();

        $jsonString = file_get_contents($this->path);                                  // das soll früher passieren

        $accountDTOList = $accountMapper->jsonToDTO($jsonString);

        $accountDTOList[] = $deposit;

        $jsonString = $accountMapper->jsonFromDTO($accountDTOList);
        file_put_contents($this->path, $jsonString);
    }

}
