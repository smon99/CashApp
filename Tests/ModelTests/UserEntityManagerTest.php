<?php declare(strict_types=1);

namespace Test\ModelTests;

use Model\UserEntityManager;
use PHPUnit\Framework\TestCase;

class UserEntityManagerTest extends TestCase
{

    public function testSaveFileTrue(): void
    {
        $userTest = [
            "user" => 'Tester',
            "eMail" => 'Tester@Tester.de',
            "password" => 'Test123#',
        ];

        $path = '/../Model/user.json';

        $userSaveTest = new UserEntityManager();
        $userSaveTest->save($userTest, $path);

        $user = json_decode(file_get_contents(__DIR__ . '/../../src/Model/user.json'), true);
        $test = false;

        foreach ($user as $key => $userRun) {
            if ($userRun["user"] === "Tester") {
                unset($user[$key]);
                $json = json_encode($user, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
                file_put_contents(__DIR__ . '/../../src/Model/user.json', $json, LOCK_EX);
                $test = true;
                break;
            }
        }

        self::assertTrue($test);
    }

    public function testSaveFileFalse(): void
    {
        $userTest = [
            "user" => 'Tester',
            "eMail" => 'Tester@Tester.de',
            "password" => 'Test123#',
        ];

        $path = '/../../Tests/ModelTests/user.json';

        $userSaveTest = new UserEntityManager();
        $userSaveTest->save($userTest, $path);

        $user = json_decode(file_get_contents(__DIR__ . '/../ModelTests/user.json'), true);
        $test = false;

        foreach ($user as $key => $userRun) {
            if ($userRun["user"] === "Tester") {
                unlink(__DIR__ . '/../ModelTests/user.json');
                $test = true;
                break;
            }
        }

        self::assertTrue($test);
    }
}