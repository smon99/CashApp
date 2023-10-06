<?php declare(strict_types=1);

namespace Test\Model;

use PHPUnit\Framework\TestCase;
use App\Model\AccountMapper;
use App\Model\AccountDTO;

class AccountMapperTest extends TestCase
{
    public function testSqlToDTO(): void
    {
        $mapper = new AccountMapper();

        $data = [
            [
                'transactionID' => 1,
                'value' => 100.0,
                'userID' => 123,
                'transactionDate' => '2023-10-07',
                'transactionTime' => '12:34:56',
                'purpose' => 'Deposit',
            ],
            [
                'transactionID' => 2,
                'value' => 50.0,
                'userID' => 456,
                'transactionDate' => '2023-10-08',
                'transactionTime' => '14:45:30',
                'purpose' => 'Withdrawal',
            ],
        ];

        $result = $mapper->sqlToDTO($data);

        $this->assertCount(2, $result);

        $this->assertInstanceOf(AccountDTO::class, $result[0]);
        $this->assertEquals(1, $result[0]->transactionID);
        $this->assertEquals(100.0, $result[0]->value);
        $this->assertEquals(123, $result[0]->userID);
        $this->assertEquals('2023-10-07', $result[0]->transactionDate);
        $this->assertEquals('12:34:56', $result[0]->transactionTime);
        $this->assertEquals('Deposit', $result[0]->purpose);

        $this->assertInstanceOf(AccountDTO::class, $result[1]);
        $this->assertEquals(2, $result[1]->transactionID);
        $this->assertEquals(50.0, $result[1]->value);
        $this->assertEquals(456, $result[1]->userID);
        $this->assertEquals('2023-10-08', $result[1]->transactionDate);
        $this->assertEquals('14:45:30', $result[1]->transactionTime);
        $this->assertEquals('Withdrawal', $result[1]->purpose);
    }

    public function testDtoToArray(): void
    {
        $mapper = new AccountMapper();
        $accountDTO = new AccountDTO();
        $accountDTO->transactionID = 1;
        $accountDTO->value = 100.0;
        $accountDTO->userID = 123;
        $accountDTO->transactionDate = '2023-10-07';
        $accountDTO->transactionTime = '12:34:56';
        $accountDTO->purpose = 'Deposit';

        $result = $mapper->dtoToArray($accountDTO);

        $expected = [
            'transactionID' => 1,
            'value' => 100.0,
            'userID' => 123,
            'transactionDate' => '2023-10-07',
            'transactionTime' => '12:34:56',
            'purpose' => 'Deposit',
        ];

        $this->assertEquals($expected, $result);
    }
}