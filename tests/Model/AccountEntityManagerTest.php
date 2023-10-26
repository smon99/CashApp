<?php declare(strict_types=1);

namespace Test\Model;

use App\Model\AccountMapper;
use App\Model\AccountRepository;
use App\Model\SqlConnector;
use PHPUnit\Framework\TestCase;
use App\Model\AccountEntityManager;
use App\Model\AccountDTO;

class AccountEntityManagerTest extends TestCase
{
    private SqlConnector $sqlConnector;
    private AccountMapper $accountMapper;
    private AccountRepository $accountRepository;

    protected function setUp(): void
    {
        $this->sqlConnector = new SqlConnector();
        $this->accountMapper = new AccountMapper();

        $this->accountRepository = new AccountRepository($this->accountMapper, $this->sqlConnector,);
    }

    public function testSaveDeposit(): void
    {
        $entityManager = new AccountEntityManager($this->sqlConnector, $this->accountMapper);

        $deposit = new AccountDTO();
        $deposit->userID = 1;
        $deposit->value = 10.0;
        $deposit->transactionTime = date('H:i:s');
        $deposit->transactionDate = date('Y-m-d');

        $entityManager->saveDeposit($deposit);
        $transaction[] = $this->accountRepository->transactionPerUserID(1);
        $result = $transaction[0][0];

        self::assertSame(10.0, $result->value);
    }

    protected function tearDown(): void
    {
        $connector = new SqlConnector();
        $connector->executeDeleteQuery("DELETE FROM Transactions;", []);
        $connector->disconnect();
    }
}
