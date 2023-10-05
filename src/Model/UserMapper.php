<?php declare(strict_types=1);

namespace App\Model;

class UserMapper
{
    public function jsonToDTO(string $jsonString): array
    {
        $data = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
        $userDTOList = [];

        foreach ($data as $entryData) {
            $userDTO = new UserDTO();
            $userDTO->userID = (int)$entryData['userID'];
            $userDTO->username = (string)$entryData['username'];
            $userDTO->email = (string)$entryData['email'];
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
                'userID' => (int)$userDTO->userID,
                'username' => (string)$userDTO->username,
                'email' => (string)$userDTO->email,
                'password' => (string)$userDTO->password,
            ];
        }
        return json_encode($entries, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}