<?php declare(strict_types=1);

namespace Test\ModelTests;

use PHPUnit\Framework\TestCase;
use Model\AccountRepository;

class AccountRepositoryTest extends TestCase
{
    public function testCalculateTimeBalance(): void
    {
        $correctInput = null;

        $testCalculateTimeBalance = new AccountRepository();
        $depositTestDataset = $testCalculateTimeBalance->calculateTimeBalance($correctInput);

        $data = json_decode(file_get_contents(__DIR__ . '/../../src/Model/account.json'), true);
        $balance = array_sum(array_column($data, "amount"));

        self::assertSame($balance, $depositTestDataset["balance"]);
    }
}