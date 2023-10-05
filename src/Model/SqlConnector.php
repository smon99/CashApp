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

    public function executeQuery(string $query): bool|\PDOStatement
    {
        $this->connect();
        return $this->pdo->query($query);
    }
}
