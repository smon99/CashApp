<?php

namespace App\Model;

class UserMapper
{
    public function jsonToDTO(string $jsonString): array
    {
        $data = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
        $userDTOList = [];

        foreach ($data as $entryData) {
            $userDTO = new UserDTO();
            $userDTO->user = (string)$entryData['user'];
            $userDTO->eMail = (string)$entryData['eMail'];
            $userDTO->password = (string)$entryData['password'];
            $userDTOList[] = $userDTO;
        }
        return $userDTOList;
    }

    public function jsonFromDTO(array $userDTOList): string
    {
        $entries = [];

        foreach ($userDTOList as $userDTO) {
            $entries[] = [
                'user' => (string)$userDTO->user,
                'eMail' => (string)$userDTO->eMail,
                'password' => (string)$userDTO->password,
            ];
        }
        return json_encode($entries, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}