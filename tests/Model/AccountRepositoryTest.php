<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\AccountEntityManager;
use App\Model\SqlConnector;
use PHPUnit\Framework\TestCase;
use App\Model\AccountRepository;
use App\Model\AccountMapper;
use App\Model\AccountDTO;

class AccountRepositoryTest extends TestCase
{
    private AccountRepository $accountRepository;
    private AccountEntityManager $accountEntityManager;

    protected function setUp(): void
    {
        $this->accountRepository = new AccountRepository(new AccountMapper(), new SqlConnector());
        $this->accountEntityManager = new AccountEntityManager(new SqlConnector(), new AccountMapper());

        $accountDTOList = [
            new AccountDTO(),
            new AccountDTO(),
            new AccountDTO(),
        ];

        $accountDTOList[0]->transactionID = 1;
        $accountDTOList[0]->value = 10.0;
        $accountDTOList[0]->userID = 1;
        $accountDTOList[0]->transactionDate = date('Y-m-d');
        $accountDTOList[0]->transactionTime = date('H:i:s');
        $accountDTOList[0]->purpose = 'deposit';

        $accountDTOList[1]->transactionID = 2;
        $accountDTOList[1]->value = 15.0;
        $accountDTOList[1]->userID = 1;
        $accountDTOList[1]->transactionDate = date('Y-m-d');
        $accountDTOList[1]->transactionTime = date('H:i:s');
        $accountDTOList[1]->purpose = 'deposit';

        $accountDTOList[2]->transactionID = 3;
        $accountDTOList[2]->value = 5.0;
        $accountDTOList[2]->userID = 1;
        $accountDTOList[2]->transactionDate = date('Y-m-d');
        $accountDTOList[2]->transactionTime = date('H:i:s');
        $accountDTOList[2]->purpose = 'deposit';

        foreach ($accountDTOList as $accountDTO) {
            $this->accountEntityManager->saveDeposit($accountDTO);
        }
    }

    public function testFetchAllTransactions(): void
    {
        $transactions = $this->accountRepository->fetchAllTransactions();
        $assertion = $transactions[0];

        self::assertSame(10.0, $assertion->value);
    }

    public function testCalculateBalance(): void
    {
        $balance = $this->accountRepository->calculateBalance(1);

        self::assertSame(30.0, $balance);
    }

    public function testCalculateBalancePerHour(): void
    {
        $balancePerHour = $this->accountRepository->calculateBalancePerHour(1);

        self::assertSame(30.0, $balancePerHour);
    }

    public function testCalculateBalancePerDay(): void
    {
        $balancePerDay = $this->accountRepository->calculateBalancePerDay(1);

        self::assertSame(30.0, $balancePerDay);
    }

    public function testTransactionPerUserID(): void
    {
        $userTransactions = $this->accountRepository->transactionPerUserID(1);
        $transactionEntity = $userTransactions[0];

        self::assertSame(10.0, $transactionEntity->value);
    }

    protected function tearDown(): void
    {
        $connector = new SqlConnector();
        $connector->executeDeleteQuery("DELETE FROM Transactions;", []);
        $connector->disconnect();
    }
}
