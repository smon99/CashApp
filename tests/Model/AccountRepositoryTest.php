<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\SqlConnector;
use App\Model\UserDTO;
use PHPUnit\Framework\TestCase;
use App\Model\AccountRepository;
use App\Model\AccountMapper;
use App\Model\AccountDTO;

class AccountRepositoryTest extends TestCase
{

    public function testCalculateBalance(): void
    {
        $accountMapper = $this->createMock(AccountMapper::class);
        $sqlConnector = $this->createMock(SqlConnector::class);

        $accountRepository = new AccountRepository($accountMapper, $sqlConnector);

        $accountDTOList = [
            new AccountDTO(),
            new AccountDTO(),
            new AccountDTO(),
        ];

        $accountDTOList[0]->transactionID = 1;
        $accountDTOList[0]->value = 40.0;
        $accountDTOList[0]->userID = 0;
        $accountDTOList[0]->transactionDate = '2023-01-01';
        $accountDTOList[0]->transactionTime = '12:00:00';
        $accountDTOList[0]->purpose = 'deposit';

        $accountDTOList[1]->transactionID = 2;
        $accountDTOList[1]->value = 30.0;
        $accountDTOList[1]->userID = 0;
        $accountDTOList[1]->transactionDate = '2023-01-02';
        $accountDTOList[1]->transactionTime = '14:00:00';
        $accountDTOList[1]->purpose = 'withdraw';

        $accountDTOList[2]->transactionID = 3;
        $accountDTOList[2]->value = 20.0;
        $accountDTOList[2]->userID = 0;
        $accountDTOList[2]->transactionDate = '2023-01-01';
        $accountDTOList[2]->transactionTime = '10:30:00';
        $accountDTOList[2]->purpose = 'deposit';

        $sqlConnector->expects($this->once())
            ->method('executeSelectAllQuery')
            ->willReturn([]);

        $accountMapper->expects($this->once())
            ->method('sqlToDTO')
            ->willReturn($accountDTOList);

        $balance = $accountRepository->calculateBalance(0);

        $this->assertEquals(90.0, $balance);
    }

    public function testCalculateBalancePerHour(): void
    {
        $accountMapper = $this->createMock(AccountMapper::class);
        $sqlConnector = $this->createMock(SqlConnector::class);

        $accountRepository = new AccountRepository($accountMapper, $sqlConnector);

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $accountDTOList = [
            new AccountDTO(),
            new AccountDTO(),
            new AccountDTO(),
        ];

        $accountDTOList[0]->transactionID = 1;
        $accountDTOList[0]->value = 40.0;
        $accountDTOList[0]->userID = 0;
        $accountDTOList[0]->transactionDate = $date;
        $accountDTOList[0]->transactionTime = $time;
        $accountDTOList[0]->purpose = 'deposit';

        $accountDTOList[1]->transactionID = 2;
        $accountDTOList[1]->value = 30.0;
        $accountDTOList[1]->userID = 0;
        $accountDTOList[1]->transactionDate = $date;
        $accountDTOList[1]->transactionTime = $time;
        $accountDTOList[1]->purpose = 'deposit';

        $accountDTOList[2]->transactionID = 3;
        $accountDTOList[2]->value = 20.0;
        $accountDTOList[2]->userID = 0;
        $accountDTOList[2]->transactionDate = $date;
        $accountDTOList[2]->transactionTime = $time;
        $accountDTOList[2]->purpose = 'deposit';

        $sqlConnector->expects($this->once())
            ->method('executeSelectAllQuery')
            ->willReturn($accountDTOList);

        $accountMapper->expects($this->once())
            ->method('sqlToDTO')
            ->willReturn($accountDTOList);

        $userID = 0;

        $balancePerHour = $accountRepository->calculateBalancePerHour($userID);

        $expectedBalancePerHour = 90.0;

        $this->assertEquals($expectedBalancePerHour, $balancePerHour);
    }

    public function testCalculateBalancePerDay(): void
    {
        $accountMapper = $this->createMock(AccountMapper::class);
        $sqlConnector = $this->createMock(SqlConnector::class);

        $accountRepository = new AccountRepository($accountMapper, $sqlConnector);

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $accountDTOList = [
            new AccountDTO(),
            new AccountDTO(),
            new AccountDTO(),
        ];

        $accountDTOList[0]->transactionID = 1;
        $accountDTOList[0]->value = 40.0;
        $accountDTOList[0]->userID = 0;
        $accountDTOList[0]->transactionDate = $date;
        $accountDTOList[0]->transactionTime = $time;
        $accountDTOList[0]->purpose = 'deposit';

        $accountDTOList[1]->transactionID = 2;
        $accountDTOList[1]->value = 30.0;
        $accountDTOList[1]->userID = 0;
        $accountDTOList[1]->transactionDate = $date;
        $accountDTOList[1]->transactionTime = $time;
        $accountDTOList[1]->purpose = 'deposit';

        $accountDTOList[2]->transactionID = 3;
        $accountDTOList[2]->value = 20.0;
        $accountDTOList[2]->userID = 0;
        $accountDTOList[2]->transactionDate = $date;
        $accountDTOList[2]->transactionTime = $time;
        $accountDTOList[2]->purpose = 'deposit';

        $sqlConnector->expects($this->once())
            ->method('executeSelectAllQuery')
            ->willReturn($accountDTOList);

        $accountMapper->expects($this->once())
            ->method('sqlToDTO')
            ->willReturn($accountDTOList);

        $userID = 0;

        $balancePerDay = $accountRepository->calculateBalancePerDay($userID);

        $expectedBalancePerHour = 90.0;

        $this->assertEquals($expectedBalancePerHour, $balancePerDay);
    }

    public function testTransactionPerUserID1(): void
    {
        $accountRepository = new AccountRepository(new AccountMapper(), new SqlConnector());

        $response = $accountRepository->transactionPerUserID(0);

        self::assertIsArray($response);
    }

    public function testTransactionPerUserID(): void
    {
        $accountMapper = $this->createMock(AccountMapper::class);
        $sqlConnector = $this->createMock(SqlConnector::class);

        $accountRepository = new AccountRepository($accountMapper, $sqlConnector);

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $accountDTOList = [
            new AccountDTO(),
            new AccountDTO(),
            new AccountDTO(),
        ];

        $accountDTOList[0]->transactionID = 1;
        $accountDTOList[0]->value = 40.0;
        $accountDTOList[0]->userID = 0;
        $accountDTOList[0]->transactionDate = $date;
        $accountDTOList[0]->transactionTime = $time;
        $accountDTOList[0]->purpose = 'deposit';

        $accountDTOList[1]->transactionID = 2;
        $accountDTOList[1]->value = 30.0;
        $accountDTOList[1]->userID = 0;
        $accountDTOList[1]->transactionDate = $date;
        $accountDTOList[1]->transactionTime = $time;
        $accountDTOList[1]->purpose = 'deposit';

        $accountDTOList[2]->transactionID = 3;
        $accountDTOList[2]->value = 20.0;
        $accountDTOList[2]->userID = 0;
        $accountDTOList[2]->transactionDate = $date;
        $accountDTOList[2]->transactionTime = $time;
        $accountDTOList[2]->purpose = 'deposit';

        $sqlConnector->expects($this->once())
            ->method('executeSelectAllQuery')
            ->willReturn($accountDTOList);

        $accountMapper->expects($this->once())
            ->method('sqlToDTO')
            ->willReturn($accountDTOList);

        $userID = 0;

        $transactions = $accountRepository->transactionPerUserID($userID);

        self::assertEquals('deposit', $transactions[0]->purpose);
    }

    protected function tearDown(): void
    {
        $connector = new SqlConnector();
        $connector->executeDeleteQuery("DELETE FROM Transactions;", []);
        $connector->disconnect();

        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
