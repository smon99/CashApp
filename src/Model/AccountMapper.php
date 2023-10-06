<?php declare(strict_types=1);

namespace App\Model;

class AccountMapper
{
    public function sqlToDTO($data): array
    {
        $collection = [];

        foreach ($data as $ENTRY) {
            $accountDTO = new AccountDTO();
            $accountDTO->transactionID = (int)$ENTRY["transactionID"];
            $accountDTO->value = (float)$ENTRY["value"];
            $accountDTO->userID = (int)$ENTRY["userID"];
            $accountDTO->transactionDate = (string)$ENTRY["transactionDate"];
            $accountDTO->transactionTime = (string)$ENTRY["transactionTime"];
            $accountDTO->purpose = (string)$ENTRY["purpose"];

            $collection[] = $accountDTO;
        }
        return $collection;
    }

    public function dtoToArray(AccountDTO $accountDTO): array
    {
        return [
            'transactionID' => $accountDTO->transactionID,
            'value' => $accountDTO->value,
            'userID' => $accountDTO->userID,
            'transactionDate' => $accountDTO->transactionDate,
            'transactionTime' => $accountDTO->transactionTime,
            'purpose' => $accountDTO->purpose,
        ];
    }
}
