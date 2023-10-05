<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\AccountMapper;
use App\Model\AccountDTO;

class AccountMapperTest extends TestCase
{

    public function testJsonToDTO(): void
    {
        $jsonString = '[{"amount": 100.0, "date": "2023-09-20", "time": "10:00:00"}]';
        $mapper = new AccountMapper();
        $accountDTOList = $mapper->jsonToDTO($jsonString);

        $this->assertIsArray($accountDTOList);
        $this->assertCount(1, $accountDTOList);

        $this->assertInstanceOf(AccountDTO::class, $accountDTOList[0]);
        $this->assertEquals(100.0, $accountDTOList[0]->value);
        $this->assertEquals('2023-09-20', $accountDTOList[0]->transactionDate);
        $this->assertEquals('10:00:00', $accountDTOList[0]->transactionTime);
    }

    public function testJsonFromDTO(): void
    {
        $entry1 = new AccountDTO();
        $entry1->value = 100.0;
        $entry1->transactionDate = '2023-09-20';
        $entry1->transactionTime = '10:00:00';

        $entry2 = new AccountDTO();
        $entry2->value = 200.0;
        $entry2->transactionDate = '2023-09-21';
        $entry2->transactionTime = '11:00:00';

        $accountDTOList = [$entry1, $entry2];

        $mapper = new AccountMapper();
        $jsonString = $mapper->jsonFromDTO($accountDTOList);

        $this->assertJson($jsonString);

        $decodedData = json_decode($jsonString, true);
        $this->assertIsArray($decodedData);
        $this->assertCount(2, $decodedData);

        $this->assertSame(100, $decodedData[0]['amount']);
        $this->assertSame('2023-09-20', $decodedData[0]['date']);
        $this->assertSame('10:00:00', $decodedData[0]['time']);
        $this->assertSame(200, $decodedData[1]['amount']);
        $this->assertSame('2023-09-21', $decodedData[1]['date']);
        $this->assertSame('11:00:00', $decodedData[1]['time']);
    }
}