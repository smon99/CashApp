<?php declare(strict_types=1);

namespace App\Model;

use JsonException;

class AccountRepository
{
    public const ACCOUNT_DEFAULT_PATH = __DIR__ . '/account.json';

    private string $path;
    private AccountMapper $accountMapper;

    public function __construct(AccountMapper $accountMapper, ?string $path = self::ACCOUNT_DEFAULT_PATH)
    {
        $this->path = $path;

        $this->accountMapper = $accountMapper;
    }

    /**
     * @return AccountDTO[]
     * @throws JsonException
     */
    public function findAll(): array
    {
        $jsonString = file_get_contents($this->path);
        return $this->accountMapper->jsonToDTO($jsonString);
    }

    public function calculateBalance(): float
    {
        $accountDTOList = $this->findAll();

        $balance = 0.0;

        foreach ($accountDTOList as $entry) {
            $balance += $entry->amount;
        }
        return $balance;
    }

    public function calculateBalancePerHour(): float
    {
        $accountDTOList = $this->findAll();

        $balancePerHour = 0.0;

        $date = strtotime(date('Y-m-d'));
        $time = strtotime(date('H:i:s'));

        foreach ($accountDTOList as $entry) {
            if (strtotime($entry->time) > $time - (60 * 60) && strtotime($entry->date) === $date) {
                $balancePerHour += $entry->amount;
            }
        }
        return $balancePerHour;
    }


    public function calculateBalancePerDay(): float
    {
        $accountDTOList = $this->findAll();

        $balancePerDay = 0.0;
        $date = date('Y-m-d');

        foreach ($accountDTOList as $entry) {
            if ($entry->date === $date) {
                $balancePerDay += $entry->amount;
            }
        }
        return $balancePerDay;
    }
}
