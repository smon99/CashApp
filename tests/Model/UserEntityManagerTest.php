<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\UserEntityManager;

class UserEntityManagerTest extends TestCase
{
    private $testFilePath = __DIR__ . '/../../tests/Model/user.json';

    public function testSaveFileTrue(): void
    {
        $userTest = [
            "user" => 'Tester',
            "eMail" => 'Tester@Tester.de',
            "password" => 'Test123#',
        ];

        $userSave = new UserEntityManager($this->testFilePath);
        $userSave->save($userTest);

        $userTestReal = [
            "user" => 'TesterReal',
            "eMail" => 'Tester@TesterReal.de',
            "password" => 'TesterReal123#',
        ];

        $userSaveTest = new UserEntityManager($this->testFilePath);
        $userSaveTest->save($userTestReal);

        $user = json_decode(file_get_contents($this->testFilePath), true);
        $test = false;

        foreach ($user as $key => $userRun) {
            if ($userRun["user"] === "TesterReal") {
                unlink(__DIR__ . '/user.json');
                $test = true;
                break;
            }
        }

        self::assertTrue($test);
    }

    public function testSaveFileFalse(): void
    {
        if (file_exists(__DIR__ . '/user.json')) {
            unlink(__DIR__ . '/user.json');
        }

        $userTest = [
            "user" => 'Tester',
            "eMail" => 'Tester@Tester.de',
            "password" => 'Test123#',
        ];

        $userSaveTest = new UserEntityManager($this->testFilePath);
        $userSaveTest->save($userTest);

        $user = json_decode(file_get_contents($this->testFilePath), true);
        $test = false;

        foreach ($user as $userRun) {
            if ($userRun["user"] === "Tester") {
                unlink(__DIR__ . '/user.json');
                $test = true;
                break;
            }
        }

        self::assertTrue($test);
    }
}
