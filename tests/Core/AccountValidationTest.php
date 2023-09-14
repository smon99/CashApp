<?php declare(strict_types=1);

namespace Test\Core;

use PHPUnit\Framework\TestCase;
use App\Core\AccountValidation;

class AccountValidationTest extends TestCase
{
    public function testSingleDepositLimit(): void
    {
        $random = random_int(1, 50);
        $amount = (float)$random;

        $validator = new AccountValidation();

        self::assertTrue($validator->singleDepositLimit($amount));
    }

    public function testHourDepositLimit(): void
    {
        $random = random_int(1, 50);
        $amount = (float)$random;

        $validator = new AccountValidation();

        self::assertTrue($validator->hourDepositLimit($amount));
    }

    public function testDayDepositLimit(): void
    {
        $random = random_int(1, 50);
        $amount = (float)$random;

        $validator = new AccountValidation();

        self::assertTrue($validator->dayDepositLimit($amount));
    }
}