<?php declare(strict_types=1);

namespace App\Model;

class AccountRepository
{
    public const ACCOUNT_DEFAULT_PATH = __DIR__ . '/account.json';

    private string $path;
    private AccountMapper $accountMapper;

    public function __construct(AccountMapper $accountMapper, ?string $path = null)
    {
        if ($path === null) {
            $path = self::ACCOUNT_DEFAULT_PATH;
        }

        $this->path = $path;

        $this->accountMapper = $accountMapper;
    }

    public function calculateBalance(): float
    {
        $jsonString = file_get_contents($this->path);
        $accountDTOList = $this->accountMapper->jsonToDTO($jsonString);

        $balance = 0.0;

        foreach ($accountDTOList as $entry) {
            $balance += $entry->amount;
        }

        return $balance;
    }
}
