<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\UserMapper;
use App\Model\UserDTO;

class UserMapperTest extends TestCase
{
    public function testSqlToDTO(): void
    {
        $userMapper = new UserMapper();

        $data = [
            ['userID' => 1, 'username' => 'user1', 'email' => 'user1@example.com', 'password' => 'password1'],
            ['userID' => 2, 'username' => 'user2', 'email' => 'user2@example.com', 'password' => 'password2'],
        ];

        $resultDTOs = $userMapper->sqlToDTO($data);

        $this->assertCount(2, $resultDTOs);
        $this->assertInstanceOf(UserDTO::class, $resultDTOs[0]);
        $this->assertInstanceOf(UserDTO::class, $resultDTOs[1]);
        $this->assertEquals(1, $resultDTOs[0]->userID);
        $this->assertEquals('user1', $resultDTOs[0]->username);
        $this->assertEquals('user1@example.com', $resultDTOs[0]->email);
        $this->assertEquals('password1', $resultDTOs[0]->password);
        $this->assertEquals(2, $resultDTOs[1]->userID);
        $this->assertEquals('user2', $resultDTOs[1]->username);
        $this->assertEquals('user2@example.com', $resultDTOs[1]->email);
        $this->assertEquals('password2', $resultDTOs[1]->password);
    }

    public function testDtoToArray(): void
    {
        $userMapper = new UserMapper();

        $userDTO = new UserDTO();
        $userDTO->userID = 100;
        $userDTO->username = 'user100';
        $userDTO->email = 'user100@example.com';
        $userDTO->password = 'Password100#';

        $resultArray = $userMapper->dtoToArray($userDTO);

        $this->assertEquals([
            'userID' => 100,
            'username' => 'user100',
            'email' => 'user100@example.com',
            'password' => 'Password100#',
        ], $resultArray);
    }
}