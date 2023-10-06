<?php declare(strict_types=1);

namespace App\Model;

use JsonException;

class AccountMapper
{
    /**
     * @param string $jsonString
     * @return AccountDTO[]
     * @throws JsonException
     */
    public function jsonToDTO(string $jsonString): array
    {
        $data = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
        $accountDTOList = [];

        foreach ($data as $entryData) {
            $accountDTO = new AccountDTO();
            $accountDTO->transactionID = (int)$entryData['transactionID'];
            $accountDTO->value = (float)$entryData['value'];
            $accountDTO->userID = (int)$entryData['userID'];
            $accountDTO->transactionDate = (string)$entryData['transactionDate'];
            $accountDTO->transactionTime = (string)$entryData['transactionTime'];
            $accountDTO->purpose = (string)$entryData['purpose'];
            $accountDTOList[] = $accountDTO;
        }

        return $accountDTOList;
    }

    public function jsonFromDTO(array $accountDTOList): string
    {
        $entries = [];

        foreach ($accountDTOList as $accountDTO) {
            $entries[] = [
                'transactionID' => (int)$accountDTO->transactionID,
                'value' => (float)$accountDTO->value,
                'userID' => (int)$accountDTO->userID,
                'transactionDate' => (string)$accountDTO->transactionDate,
                'transactionTime' => (string)$accountDTO->transactionTime,
                'purpose' => (string)$accountDTO->purpose,
            ];
        }

        return json_encode($entries, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

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
