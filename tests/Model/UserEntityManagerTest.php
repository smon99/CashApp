<?php declare(strict_types=1);

namespace Test\Model;

use App\Core\Container;
use App\Model\SqlConnector;
use App\Model\UserMapper;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;
use App\Model\UserEntityManager;
use App\Model\UserDTO;

class UserEntityManagerTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private UserRepository $userRepository;
    private UserEntityManager $userEntityManager;

    protected function setUp(): void
    {
        $this->sqlConnector = new SqlConnector();
        $userMapper = new UserMapper();

        $this->userRepository = new UserRepository($this->sqlConnector, $userMapper);
        $this->userEntityManager = new UserEntityManager($this->sqlConnector, $userMapper);
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->execute("DELETE FROM Users;", []);
    }

    public function testSaveUser(): void
    {
        $user = new UserDTO();
        $user->username = 'Tester';
        $user->email = 'Tester@Tester.de';
        $user->password = 'Tester123#';

        $this->userEntityManager->save($user);

        $users[] = $this->userRepository->fetchAllUsers();
        $userEntity = $users[0][0];

        self::assertSame('Tester', $userEntity->username);
    }
}
