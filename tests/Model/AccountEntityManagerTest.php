<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\AccountEntityManager;

class AccountEntityManagerTest extends TestCase
{
    private $testFilePath = __DIR__ . '/../../tests/Model/account.json';

    public function testSaveDeposit(): void
    {
        $initialData = [
            ["amount" => "100.00"],
            ["amount" => "200.00"],
        ];

        $testData = json_encode($initialData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents($this->testFilePath, $testData);

        $newDeposit = [
            "amount" => "50.00",
            "date" => "2023-09-08",
            "time" => "14:30:00",
        ];

        $accountEntityManager = new AccountEntityManager($this->testFilePath);
        $accountEntityManager->saveDeposit($newDeposit);

        $updatedData = json_decode(file_get_contents($this->testFilePath), true, JSON_THROW_ON_ERROR);

        self::assertSame($updatedData[count($updatedData) - 1]['amount'], '50.00');

        unlink(__DIR__ . '/account.json');
    }
}
