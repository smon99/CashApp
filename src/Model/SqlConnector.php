<?php declare(strict_types=1);

namespace App\Model;

use PDO;
use PDOException;

class SqlConnector
{
    private ?PDO $pdo = null;

    public function __construct()
    {
    }

    private function connect(): void
    {
        $name = $_ENV['DATABASE'] ?? 'cash';

        try {
            $host = 'localhost:3336';
            $user = 'root';
            $password = 'nexus123';

            $this->pdo = new PDO("mysql:host=$host;dbname=$name", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            echo 'Error in SQL connector! ' . $exception->getMessage();
        }
    }

    public function executeSelectAllQuery($query): bool|array|null
    {
        try {
            $this->connect();

            return $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $exception) {
            echo 'Error executing SELECT query: ' . $exception->getMessage();
            return null;
        }
    }

    public function executeInsertQuery(string $query, array $params): string
    {
        $this->connect();

        try {
            $statement = $this->pdo->prepare($query);

            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value, PDO::PARAM_STR);
            }

            $statement->execute();

            return $this->pdo->lastInsertId();
        } catch (PDOException $exception) {
            die("Query failed: " . $exception->getMessage());
        }
    }

    public function executeUpdateQuery(string $query, array $params): void
    {
        $this->connect();

        try {
            $statement = $this->pdo->prepare($query);

            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value, PDO::PARAM_STR);
            }

            $statement->execute();
        } catch (PDOException $exception) {
            die("Query failed: " . $exception->getMessage());
        }
    }

    public function executeDeleteQuery(string $query, array $params): void
    {
        $this->connect();

        try {
            $statement = $this->pdo->prepare($query);

            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value, PDO::PARAM_STR);
            }

            $statement->execute();
        } catch (PDOException $exception) {
            die("Query failed: " . $exception->getMessage());
        }
    }

}
