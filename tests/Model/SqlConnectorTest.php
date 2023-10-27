<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\SqlConnector;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertIsObject;
use PDOException;

class SqlConnectorTest extends TestCase
{
    private SqlConnector $sqlConnector;

    protected function setUp(): void
    {
        $this->sqlConnector = new SqlConnector();
    }

    protected function tearDown(): void
    {
        $this->sqlConnector->execute("DELETE FROM Transactions;", []);
        $this->sqlConnector->execute("DELETE FROM Users;", []);
        $this->sqlConnector->disconnect();
    }

    public function testExecuteSelectAllQueryValid(): void
    {
        $query = "SELECT * FROM Users";

        $result = $this->sqlConnector->executeSelectAllQuery($query);

        assertIsArray($result);
    }

    public function testExecuteSelectAllQueryInvalid(): void
    {
        $query = "SELECT * FROM Bogus";

        $this->expectException(PDOException::class);
        $result = $this->sqlConnector->executeSelectAllQuery($query);

        assertIsObject($result);
    }

    public function testExecuteInsertQueryValid(): void
    {
        $query = "INSERT INTO Transactions (value, userID, transactionDate, transactionTime, purpose) VALUES (:value, :userID, :transactionDate, :transactionTime, :purpose)";

        $params = [
            ':value' => 1.0,
            ':userID' => 0,
            ':transactionDate' => '2023-10-09',
            ':transactionTime' => '10:00:00',
            ':purpose' => 'testing',
        ];

        $result = $this->sqlConnector->execute($query, $params);

        self::assertIsString($result);
    }

    public function testExecuteInsertQueryInvalid(): void
    {
        $query = "INSERT INTO TransactionsNoNo (value, userID, transactionDate, transactionTime, purpose) VALUES (:value, :userID, :transactionDate, :transactionTime, :purpose)";

        $params = [
            ':value' => 1.0,
            ':userID' => 0,
            ':transactionDate' => '2023-10-09',
            ':transactionTime' => '10:00:00',
            ':purpose' => 'testing',
        ];

        $this->expectException(PDOException::class);
        $this->sqlConnector->execute($query, $params);
    }

    public function testExecuteDeleteQuery(): void
    {
        $query = "DELETE FROM Transactions;";
        $params = [];

        $result = $this->sqlConnector->execute($query, $params);

        self::assertSame("0", $result);
    }

    public function testExecuteDeleteQueryError(): void
    {
        $this->expectException(PDOException::class);
        $this->sqlConnector->execute('invalid', []);
    }
}
