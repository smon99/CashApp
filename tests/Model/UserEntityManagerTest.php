<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\SqlConnector;
use PHPUnit\Framework\TestCase;
use App\Model\UserEntityManager;
use App\Model\UserDTO;
use App\Model\UserMapper;

class UserEntityManagerTest extends TestCase
{
    public function testSaveUser(): void
    {
        $sqlConnector = $this->createMock(SqlConnector::class);
        $userMapper = $this->createMock(UserMapper::class);

        $expectedQuery = "INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)";
        $expectedData = ['username' => 'testuser', 'email' => 'test@example.com', 'password' => 'password'];
        $expectedParams = [
            ':username' => 'testuser',
            ':email' => 'test@example.com',
            ':password' => 'password',
        ];

        $userMapper->expects($this->once())
            ->method('dtoToArray')
            ->willReturn($expectedData);

        $sqlConnector->expects($this->once())
            ->method('executeInsertQuery')
            ->with($expectedQuery, $expectedParams);

        $userDTO = new UserDTO();
        $userDTO->username = 'testuser';
        $userDTO->email = 'test@example.com';
        $userDTO->password = 'password';

        $entityManager = new UserEntityManager($sqlConnector, $userMapper);
        $entityManager->save($userDTO);
    }
}
