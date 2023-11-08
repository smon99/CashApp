<?php declare(strict_types=1);

namespace App\Model;

use App\Core\Container;

class UserEntityManager
{
    public function __construct(private SqlConnector $sqlConnector, private UserMapper $userMapper)
    {
    }

    public function save(UserDTO $userDTO): void
    {
        $query = "INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)";

        $data = $this->userMapper->dtoToArray($userDTO);

        $params = [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => $data['password'],
        ];

        $this->sqlConnector->execute($query, $params);
    }
}
