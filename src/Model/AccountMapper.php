<?php

namespace App\Model;

class AccountMapper
{
    public function JsonToDTO(): array
    {
        $data = json_decode(file_get_contents(__DIR__ . '/account.json'), true, 512, JSON_THROW_ON_ERROR);
        $accountDTOList = [];

        foreach ($data as $entryData) {
            $accountDTO = new AccountDTO();
            $accountDTO->amount = ($entryData['amount']);
            $accountDTO->date = ($entryData['date']);
            $accountDTO->time = ($entryData['time']);
            $accountDTOList[] = $accountDTO;
        }
        return $accountDTOList;
    }
}