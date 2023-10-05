<?php declare(strict_types=1);

namespace App\Model;

use mysql_xdevapi\Collection;

class UserMapper
{
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

    public function sqlToDTO($data): array
    {
        $collection = [];

        foreach ($data as $ENTRY) {
            $userDTO = new UserDTO();
            $userDTO->userID = (int)$ENTRY["userID"];
            $userDTO->username = (string)$ENTRY["username"];
            $userDTO->email = (string)$ENTRY["email"];
            $userDTO->password = (string)$ENTRY["password"];

            $collection[] = $userDTO;
        }
        return $collection;
    }

    public function dtoToArray(UserDTO $userDTO): array
    {
        return [
            'userID' => $userDTO->userID,
            'username' => $userDTO->username,
            'email' => $userDTO->email,
            'password' => $userDTO->password,
        ];
    }

}