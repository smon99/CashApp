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
            $accountDTO->amount = (float)$entryData['amount'];
            $accountDTO->date = (string)$entryData['date'];
            $accountDTO->time = (string)$entryData['time'];
            $accountDTOList[] = $accountDTO;
        }

        return $accountDTOList;
    }

    public function jsonFromDTO(array $accountDTOList): string
    {
        $entries = [];

        foreach ($accountDTOList as $accountDTO) {
            $entries[] = [
                'amount' => (float)$accountDTO->amount,
                'date' => (string)$accountDTO->date,
                'time' => (string)$accountDTO->time,
            ];
        }

        return json_encode($entries, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
