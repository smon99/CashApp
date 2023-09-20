<?php declare(strict_types=1);

namespace Test\Core;

use PHPUnit\Framework\TestCase;
use App\Core\AccountValidation;

class AccountValidationTest extends TestCase
{
    public function testGetCorrectAmount(): void
    {
        $acceptableInput1 = '1.000,00';
        $acceptableInput2 = '1.000';
        $acceptableInput3 = '1000';

        $validator = new AccountValidation();

        self::assertSame(1000.00, $validator->getCorrectAmount($acceptableInput1));
        self::assertSame(1000.00, $validator->getCorrectAmount($acceptableInput2));
        self::assertSame(1000.00, $validator->getCorrectAmount($acceptableInput3));
    }

    public function testExistsIsNumeric(): void
    {
        $acceptableInput = 1000.0;

        $validator = new AccountValidation();

        self::assertTrue($validator->existsIsNumeric($acceptableInput));
    }

    public function testExistsIsNumericFalse(): void
    {
        $stringInput = 'hey';

        $validator = new AccountValidation();

        self::assertFalse($validator->existsIsNumeric($stringInput));
    }

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

    public function testValidateAllCriteria(): void
    {
        $acceptableInput1 = '30,00';
        $acceptableInput2 = '0.030,00';
        $acceptableInput3 = '30';

        $validator = new AccountValidation();

        self::assertSame(30.0, $validator->validateAllCriteria($acceptableInput1));
        self::assertSame(30.0, $validator->validateAllCriteria($acceptableInput2));
        self::assertSame(30.0, $validator->validateAllCriteria($acceptableInput3));
    }
}