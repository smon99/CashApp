<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\AccountEntityManager;
use App\Model\AccountDTO;

class AccountEntityManagerTest extends TestCase
{
    private $tempJsonFile;

    protected function setUp(): void
    {
        $this->tempJsonFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($this->tempJsonFile, '[]');
    }

    public function testSaveDeposit()
    {
        $jsonFilePath = $this->tempJsonFile;

        $entityManager = new AccountEntityManager($jsonFilePath);

        $deposit = new AccountDTO();
        $deposit->amount = 100.0;
        $deposit->date = '2023-09-20';
        $deposit->time = '10:00:00';

        $entityManager->saveDeposit($deposit);

        $jsonContents = file_get_contents($jsonFilePath);

        $decodedData = json_decode($jsonContents, true);

        $this->assertIsArray($decodedData);
        $this->assertCount(1, $decodedData);
        $this->assertSame(100, $decodedData[0]['amount']);
        $this->assertSame('2023-09-20', $decodedData[0]['date']);
        $this->assertSame('10:00:00', $decodedData[0]['time']);
    }

    public function testConstructor(): void
    {
        $entityManager = new AccountEntityManager();

        self::assertInstanceOf(AccountEntityManager::class, $entityManager);
    }

    protected function tearDown(): void
    {
        unlink($this->tempJsonFile);
    }
}
