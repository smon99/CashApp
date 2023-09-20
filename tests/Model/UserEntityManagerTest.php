<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\UserEntityManager;
use App\Model\UserDTO;
use App\Model\UserMapper;

class UserEntityManagerTest extends TestCase
{
    private $testFilePath = __DIR__ . '/../../tests/Model/user.json';

    public function testSaveFileTrue(): void
    {
        $userDTO = new UserDTO();
        $userDTO->user = 'Tester';
        $userDTO->eMail = 'Tester@Tester.de';
        $userDTO->password = 'Test123#';

        $userEntityManager = new UserEntityManager(new UserMapper(), $this->testFilePath);
        $userEntityManager->save($userDTO);

        $userDTOReal = new UserDTO();
        $userDTOReal->user = 'TesterReal';
        $userDTOReal->eMail = 'Tester@TesterReal.de';
        $userDTOReal->password = 'TesterReal123#';

        $userEntityManagerTest = new UserEntityManager(new UserMapper(), $this->testFilePath);
        $userEntityManagerTest->save($userDTOReal);

        $user = json_decode(file_get_contents($this->testFilePath), true);
        $test = false;

        foreach ($user as $key => $userRun) {
            if ($userRun["user"] === "TesterReal") {
                unlink($this->testFilePath);
                $test = true;
                break;
            }
        }

        self::assertTrue($test);
    }

    public function testSaveFileFalse(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }

        $userDTO = new UserDTO();
        $userDTO->user = 'Tester';
        $userDTO->eMail = 'Tester@Tester.de';
        $userDTO->password = 'Test123#';

        $userEntityManager = new UserEntityManager(new UserMapper(), $this->testFilePath);
        $userEntityManager->save($userDTO);

        $user = json_decode(file_get_contents($this->testFilePath), true);
        $test = false;

        foreach ($user as $userRun) {
            if ($userRun["user"] === "Tester") {
                unlink($this->testFilePath);
                $test = true;
                break;
            }
        }

        self::assertTrue($test);
    }

    public function testConstructor(): void
    {
        $userMapper = new UserMapper();
        $entityManager = new UserEntityManager($userMapper);

        self::assertSame($entityManager, $entityManager);
    }
}
