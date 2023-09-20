<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\UserRepository;
use App\Model\UserDTO;
use App\Model\UserMapper;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private $testFilePath = __DIR__ . '/../../tests/Model/user.json';

    public function setUp(): void
    {
        $userDTO = new UserDTO();
        $userDTO->user = 'TestUser';
        $userDTO->eMail = 'TestUser@TestUser.de';
        $userDTO->password = '$2y$10$mUhklPZSOKe6ywT7pl0KnO44vUlBwYYUUQxltAdbUL5R44MJDJgkq'; // TestUser123#

        $userMapper = new UserMapper();
        $userJson = $userMapper->jsonFromDTO([$userDTO]);
        file_put_contents($this->testFilePath, $userJson);
    }

    public function tearDown(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function testFindByUsername(): void
    {
        $userRepository = new UserRepository(new UserMapper(), $this->testFilePath);
        $usernameTestDataset = $userRepository->findByUsername('TestUser');

        self::assertSame('TestUser', $usernameTestDataset->user);
        self::assertSame('TestUser@TestUser.de', $usernameTestDataset->eMail);
        self::assertSame('$2y$10$mUhklPZSOKe6ywT7pl0KnO44vUlBwYYUUQxltAdbUL5R44MJDJgkq', $usernameTestDataset->password); // TestUser123#
    }

    public function testFindByUsernameNull(): void
    {
        $userRepository = new UserRepository(new UserMapper(), $this->testFilePath);
        $usernameNullTestDataset = $userRepository->findByUsername('Non Existing');

        self::assertNull($usernameNullTestDataset);
    }

    public function testFindByMail(): void
    {
        $userRepository = new UserRepository(new UserMapper(), $this->testFilePath);
        $mailTestDataset = $userRepository->findByMail('TestUser@TestUser.de');

        self::assertSame('TestUser', $mailTestDataset->user);
        self::assertSame('TestUser@TestUser.de', $mailTestDataset->eMail);
        self::assertSame('$2y$10$mUhklPZSOKe6ywT7pl0KnO44vUlBwYYUUQxltAdbUL5R44MJDJgkq', $mailTestDataset->password); // TestUser123#
    }

    public function testFindByMailNull(): void
    {
        $userRepository = new UserRepository(new UserMapper(), $this->testFilePath);
        $mailNullTestDataset = $userRepository->findByMail('Non Existing');

        self::assertNull($mailNullTestDataset);
    }
}
