<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\SqlConnector;
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
        $accountDTOList[0]->value = 50.0;
        $accountDTOList[0]->userID = 1;
        $accountDTOList[0]->transactionDate = '2023-01-01';
        $accountDTOList[0]->transactionTime = '12:00:00';
        $accountDTOList[0]->purpose = 'deposit';

        $accountDTOList[1]->transactionID = 2;
        $accountDTOList[1]->value = 30.0;
        $accountDTOList[1]->userID = 1;
        $accountDTOList[1]->transactionDate = '2023-01-02';
        $accountDTOList[1]->transactionTime = '14:00:00';
        $accountDTOList[1]->purpose = 'withdraw';

        $accountDTOList[2]->transactionID = 3;
        $accountDTOList[2]->value = 20.0;
        $accountDTOList[2]->userID = 2;
        $accountDTOList[2]->transactionDate = '2023-01-01';
        $accountDTOList[2]->transactionTime = '10:30:00';
        $accountDTOList[2]->purpose = 'deposit';

        $sqlConnector->expects($this->once())
            ->method('executeSelectAllQuery')
            ->willReturn([]);

        $accountMapper->expects($this->once())
            ->method('sqlToDTO')
            ->willReturn($accountDTOList);

        $balance = $accountRepository->calculateBalance(1);

        $this->assertEquals(80.0, $balance);
    }
}
