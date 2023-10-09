<?php declare(strict_types=1);

namespace App\Model;

use PDO;

class SqlConnector
{
    private ?PDO $pdo = null;

    public function __construct()
    {
    }

    private function connect(): PDO
    {
        $name = $_ENV['DATABASE'] ?? 'cash';

        $host = 'localhost:3336';
        $user = 'root';
        $password = 'nexus123';

        $connection = $this->pdo = new PDO("mysql:host=$host;dbname=$name", $user, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }

    public function executeSelectAllQuery($query): array
    {
        $this->connect();

        return $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function executeInsertQuery(string $query, array $params): string
    {
        $this->connect();
        $statement = $this->pdo->prepare($query);

        foreach ($params as $param => $value) {
            $statement->bindValue($param, $value, PDO::PARAM_STR);
        }

        $statement->execute();

        return $this->pdo->lastInsertId();
    }
}
