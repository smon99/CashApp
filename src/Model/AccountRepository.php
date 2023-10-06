<?php declare(strict_types=1);

namespace App\Model;

class AccountRepository
{
    private AccountMapper $accountMapper;
    private SqlConnector $sqlConnector;

    public function __construct(AccountMapper $accountMapper, SqlConnector $sqlConnector)
    {
        $this->sqlConnector = $sqlConnector;
        $this->accountMapper = $accountMapper;
    }


    public function calculateBalance(int $userID): float
    {
        $accountDTOList = $this->fetchAllTransactions();

        $balance = 0.0;

        foreach ($accountDTOList as $entry) {
            if ($entry->userID === $userID) {
                $balance += $entry->value;
            }
        }
        return $balance;
    }

    public function calculateBalancePerHour(): float
    {
        $accountDTOList = $this->fetchAllTransactions();

        $balancePerHour = 0.0;

        $date = strtotime(date('Y-m-d'));
        $time = strtotime(date('H:i:s'));

        foreach ($accountDTOList as $entry) {
            if (strtotime($entry->transactionTime) > $time - (60 * 60) && strtotime($entry->transactionDate) === $date) {
                $balancePerHour += $entry->value;
            }
        }
        return $balancePerHour;
    }

    public function calculateBalancePerDay(): float
    {
        $accountDTOList = $this->fetchAllTransactions();

        $balancePerDay = 0.0;
        $date = date('Y-m-d');

        foreach ($accountDTOList as $entry) {
            if ($entry->transactionDate === $date) {
                $balancePerDay += $entry->value;
            }
        }
        return $balancePerDay;
    }

    public function fetchAllTransactions(): array
    {
        $query = "SELECT * FROM Transactions";
        $data = $this->sqlConnector->executeSelectAllQuery($query);
        return $this->accountMapper->sqlToDTO($data);
    }
}
