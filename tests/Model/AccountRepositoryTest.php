<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\AccountEntityManager;
use App\Model\AccountRepository;
use App\Model\AccountMapper;
use App\Model\AccountDTO;

class AccountRepositoryTest extends TestCase
{
    private $tempJsonFile;

    protected function setUp(): void
    {
        $this->tempJsonFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($this->tempJsonFile, '[]');
    }

    public function testCalculateBalanceWithEmptyFile(): void
    {
        $jsonFilePath = $this->tempJsonFile;

        $accountMapper = new AccountMapper();
        $repository = new AccountRepository($accountMapper, $jsonFilePath);

        $balance = $repository->calculateBalance();

        $this->assertSame(0.0, $balance);
    }

    public function testCalculateBalanceWithNonEmptyFile(): void
    {
        $jsonFilePath = $this->tempJsonFile;

        $accountMapper = new AccountMapper();
        $repository = new AccountRepository($accountMapper, $jsonFilePath);

        $deposit1 = new AccountDTO();
        $deposit1->amount = 100.0;
        $deposit1->date = '2023-09-20';
        $deposit1->time = '10:00:00';

        $deposit2 = new AccountDTO();
        $deposit2->amount = 200.0;
        $deposit2->date = '2023-09-21';
        $deposit2->time = '11:00:00';

        $entityManager = new AccountEntityManager($jsonFilePath);

        $entityManager->saveDeposit($deposit1);
        $entityManager->saveDeposit($deposit2);

        $balance = $repository->calculateBalance();

        $this->assertSame(300.0, $balance);
    }

    public function testConstructor(): void
    {
        $accountMapper = new AccountMapper();
        $accountRepository = new AccountRepository($accountMapper);
        $balance = $accountRepository->calculateBalance();

        self::assertSame($accountRepository->calculateBalance(), $balance);
    }

    protected function tearDown(): void
    {
        unlink($this->tempJsonFile);
    }
}
