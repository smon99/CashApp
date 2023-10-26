<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\SqlConnector;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;
use App\Model\UserEntityManager;
use App\Model\UserDTO;
use App\Model\UserMapper;

class UserEntityManagerTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private UserMapper $userMapper;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->sqlConnector = new SqlConnector();
        $this->userMapper = new UserMapper();

        $this->userRepository = new UserRepository($this->userMapper, $this->sqlConnector);
    }

    public function testSaveUser(): void
    {
        $entityManager = new UserEntityManager($this->sqlConnector, $this->userMapper);

        $user = new UserDTO();
        $user->username = 'Tester';
        $user->email = 'Tester@Tester.de';
        $user->password = 'Tester123#';

        $entityManager->save($user);

        $users[] = $this->userRepository->fetchAllUsers();
        $userEntity = $users[0][0];

        self::assertSame('Tester', $userEntity->username);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->executeDeleteQuery("DELETE FROM Users;", []);
    }
}
