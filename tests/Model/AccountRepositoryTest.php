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

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $deposit1 = new AccountDTO();
        $deposit1->amount = 100.0;
        $deposit1->date = $date;
        $deposit1->time = $time;

        $deposit2 = new AccountDTO();
        $deposit2->amount = 200.0;
        $deposit2->date = $date;
        $deposit2->time = $time;

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

    public function testCalculateBalancePerHourWithEmptyFile(): void
    {
        $jsonFilePath = $this->tempJsonFile;

        $accountMapper = new AccountMapper();
        $repository = new AccountRepository($accountMapper, $jsonFilePath);

        $balance = $repository->calculateBalancePerHour();

        $this->assertSame(0.0, $balance);
    }

    public function testCalculateBalancePerHourWithNonEmptyFile(): void
    {
        $jsonFilePath = $this->tempJsonFile;

        $accountMapper = new AccountMapper();
        $repository = new AccountRepository($accountMapper, $jsonFilePath);

        $date = date('Y-m-d');
        $time = date('H:i:s');
        $amount1 = 100.0;
        $amount2 = 200.0;

        $deposit1 = new AccountDTO();
        $deposit1->amount = $amount1;
        $deposit1->date = $date;
        $deposit1->time = $time;

        $deposit2 = new AccountDTO();
        $deposit2->amount = $amount2;
        $deposit2->date = $date;
        $deposit2->time = $time;

        $entityManager = new AccountEntityManager($jsonFilePath);

        $entityManager->saveDeposit($deposit1);
        $entityManager->saveDeposit($deposit2);

        $this->assertSame(300.0, $repository->calculateBalancePerHour());
    }

    public function testCalculateBalancePerDayWithEmptyFile(): void
    {
        $jsonFilePath = $this->tempJsonFile;

        $accountMapper = new AccountMapper();
        $repository = new AccountRepository($accountMapper, $jsonFilePath);

        $balance = $repository->calculateBalancePerDay();

        $this->assertSame(0.0, $balance);
    }

    public function testCalculateBalancePerDayWithNonEmptyFile(): void
    {
        $jsonFilePath = $this->tempJsonFile;

        $date = date('Y-m-d');

        $accountMapper = new AccountMapper();

        $deposit1 = new AccountDTO();
        $deposit1->amount = 100.0;
        $deposit1->date = $date;
        $deposit1->time = '10:00:00';

        $deposit2 = new AccountDTO();
        $deposit2->amount = 200.0;
        $deposit2->date = $date;
        $deposit2->time = '11:00:00';

        $entityManager = new AccountEntityManager($jsonFilePath);

        $entityManager->saveDeposit($deposit1);
        $entityManager->saveDeposit($deposit2);

        $repository = new AccountRepository($accountMapper, $jsonFilePath);
        $balance = $repository->calculateBalancePerDay();

        $this->assertSame(300.0, $balance);
    }

    protected function tearDown(): void
    {
        unlink($this->tempJsonFile);
    }
}
