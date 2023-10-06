<?php declare(strict_types=1);

namespace App\Model;

class UserEntityManager
{
    private UserMapper $userMapper;
    private SqlConnector $sqlConnector;

    public function __construct(SqlConnector $sqlConnector, UserMapper $userMapper)
    {
        $this->sqlConnector = $sqlConnector;
        $this->userMapper = $userMapper;
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

        $this->sqlConnector->executeInsertQuery($query, $params);
    }

    public function deleteUser(UserDTO $userDTO): void
    {
        $query = "DELETE FROM Users WHERE userID = :userID";

        $params = [
            ':userID' => $userDTO->userID,
        ];

        $this->sqlConnector->executeDeleteUserQuery($query, $params);
    }

}
