<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testFindByUsername(): void
    {
        $testFindByUsername = new UserRepository(null);
        $usernameTestDataset = $testFindByUsername->findByUsername('TestUser');

        self::assertSame('TestUser', $usernameTestDataset['user']);
        self::assertSame('TestUser@TestUser.de', $usernameTestDataset['eMail']);
        self::assertSame('$2y$10$mUhklPZSOKe6ywT7pl0KnO44vUlBwYYUUQxltAdbUL5R44MJDJgkq', $usernameTestDataset['password']);     //TestUser123#
    }

    public function testFindByUsernameNull(): void
    {
        $testFindByUsernameNull = new UserRepository(null);
        $usernameNullTestDataset = $testFindByUsernameNull->findByUsername('Non Existing');

        self::assertNull($usernameNullTestDataset);
    }

    public function testFindByMail(): void
    {
        $testFindByMail = new UserRepository(null);
        $mailTestDataset = $testFindByMail->findByMail('TestUser@TestUser.de');

        self::assertSame('TestUser', $mailTestDataset['user']);
        self::assertSame('TestUser@TestUser.de', $mailTestDataset['eMail']);
        self::assertSame('$2y$10$mUhklPZSOKe6ywT7pl0KnO44vUlBwYYUUQxltAdbUL5R44MJDJgkq', $mailTestDataset['password']);     //TestUser123#
    }

    public function testFindByMailNull(): void
    {
        $testFindByMailNull = new UserRepository(null);
        $mailNullTestDataset = $testFindByMailNull->findByMail('Non Existing');

        self::assertNull($mailNullTestDataset);
    }
}