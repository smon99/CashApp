<?php declare(strict_types=1);

namespace App\Model;

use App\Core\Container;

class UserRepository
{
    public function __construct(private SqlConnector $sqlConnector, private UserMapper $userMapper)
    {
    }

    public function fetchAllUsers(): array
    {
        $query = "SELECT * FROM Users";
        $data = $this->sqlConnector->executeSelectAllQuery($query);
        return $this->userMapper->sqlToDTO($data);
    }

    public function findByMail(string $mailCheck): ?UserDTO
    {
        $data = $this->fetchAllUsers();

        foreach ($data as $dataset) {
            if ($mailCheck === $dataset->email) {
                return $dataset;
            }
        }
        return null;
    }

    public function findByUsername(string $userCheck): ?UserDTO
    {
        $data = $this->fetchAllUsers();

        foreach ($data as $dataset) {
            if ($userCheck === $dataset->username) {
                return $dataset;
            }
        }
        return null;
    }
}
