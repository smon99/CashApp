<?php declare(strict_types=1);

namespace App\Model;

class AccountEntityManager
{
    private SqlConnector $sqlConnector;
    private AccountMapper $accountMapper;

    public function __construct(SqlConnector $sqlConnector, AccountMapper $accountMapper)
    {
        $this->sqlConnector = $sqlConnector;
        $this->accountMapper = $accountMapper;
    }

    public function saveDeposit(AccountDTO $deposit): void
    {
        $query = "INSERT INTO Transactions (value, userID, transactionDate, transactionTime, purpose) VALUES (:value, :userID, :transactionDate, :transactionTime, :purpose)";

        $data = $this->accountMapper->dtoToArray($deposit);

        $params = [
            ':value' => $data['value'],
            ':userID' => $data['userID'],
            ':transactionDate' => $data['transactionDate'],
            ':transactionTime' => $data['transactionTime'],
            ':purpose' => $data['purpose'],
        ];

        $this->sqlConnector->executeInsertQuery($query, $params);
    }
}
