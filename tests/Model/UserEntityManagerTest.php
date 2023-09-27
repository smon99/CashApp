<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\UserRepository;
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

        $test = false;

        $userRepository = new UserRepository(new UserMapper(), $this->testFilePath);
        $match = $userRepository->findByUsername('TesterReal');

        if ($match !== null) {
            unlink($this->testFilePath);
            $test = true;
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

        $test = false;

        $userRepository = new UserRepository(new UserMapper(), $this->testFilePath);
        $match = $userRepository->findByUsername('Tester');

        if ($match !== null) {
            unlink($this->testFilePath);
            $test = true;
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
