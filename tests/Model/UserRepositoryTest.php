<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\UserEntityManager;
use PHPUnit\Framework\TestCase;
use App\Model\UserDTO;
use App\Model\UserRepository;
use App\Model\UserMapper;
use App\Model\SqlConnector;

class UserRepositoryTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $userMapper = new UserMapper();
        $this->sqlConnector = new SqlConnector();

        $userEntityManager = new UserEntityManager($this->sqlConnector, $userMapper);
        $this->userRepository = new UserRepository($userMapper, $this->sqlConnector);

        $userEntityManager->save($this->createUserDTO(1, 'user1', 'user1@example.com', 'password1'));
        $userEntityManager->save($this->createUserDTO(2, 'user2', 'user2@example.com', 'password2'));
        $userEntityManager->save($this->createUserDTO(3, 'user3', 'user3@example.com', 'password3'));
    }

    public function testFetchAllUsers(): void
    {
        $users = $this->userRepository->fetchAllUsers();
        $this->assertCount(3, $users);
    }

    public function testFindByMail(): void
    {
        $user = $this->userRepository->findByMail('user2@example.com');
        $this->assertInstanceOf(UserDTO::class, $user);
        $this->assertEquals('user2@example.com', $user->email);
    }

    public function testFindByMailNotFound(): void
    {
        $user = $this->userRepository->findByMail('nonexistent@example.com');
        $this->assertNull($user);
    }

    public function testFindByUsername(): void
    {
        $user = $this->userRepository->findByUsername('user3');
        $this->assertInstanceOf(UserDTO::class, $user);
        $this->assertEquals('user3', $user->username);
    }

    public function testFindByUsernameNotFound(): void
    {
        $user = $this->userRepository->findByUsername('nonexistentuser');
        $this->assertNull($user);
    }

    private function createUserDTO(int $userID, string $username, string $email, string $password): UserDTO
    {
        $userDTO = new UserDTO();
        $userDTO->userID = $userID;
        $userDTO->username = $username;
        $userDTO->email = $email;
        $userDTO->password = $password;
        return $userDTO;
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->executeDeleteQuery("DELETE FROM Users;", []);
    }
}
