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
            $accountDTO->userID = (int)$entryData['userID'];
            $accountDTO->value = (float)$entryData['value'];
            $accountDTO->transactionDate = (string)$entryData['transactionDate'];
            $accountDTO->transactionTime = (string)$entryData['transactionTime'];
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
                'userID' => (int)$accountDTO->userID,
                'value' => (float)$accountDTO->value,
                'transactionDate' => (string)$accountDTO->transactionDate,
                'transactionTime' => (string)$accountDTO->transactionTime,
            ];
        }

        return json_encode($entries, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
