<?php declare(strict_types=1);

namespace Test\Core;

use App\Core\Account\DayValidator;
use App\Core\Account\HourValidator;
use App\Core\Account\SingleValidator;
use PHPUnit\Framework\TestCase;
use App\Core\AccountValidation;
use App\Core\Account\AccountValidationException;

class AccountValidationTest extends TestCase
{
    public function testDayDepositLimit(): void
    {
        $random = random_int(1, 50);
        $amount = (float)$random;

        $validator = new AccountValidation();

        try {
            $validator->collectErrors($amount);
            self::assertTrue(true);
        } catch (AccountValidationException $e) {
            self::fail("Validation should not have thrown an exception: " . $e->getMessage());
        }
    }

    public function testCollectErrorsNotTrue(): void
    {
        $validator = new AccountValidation(new DayValidator(), new HourValidator(), new SingleValidator());
        $amount = 510;

        try {
            $validator->collectErrors($amount);
            self::fail("Validation should have thrown an exception.");
        } catch (AccountValidationException $e) {
            self::assertSame('Tägliches Einzahlungslimit von 500€ überschritten!', $e->getMessage());
        }
    }

    public function testCollectErrorsTrue(): void
    {
        $validator = new AccountValidation(new DayValidator(), new HourValidator(), new SingleValidator());

        try {
            $amount = 20;
            $validator->collectErrors($amount);
            self::assertTrue(true);
        } catch (AccountValidationException $e) {
            self::fail("Validation should not have thrown an exception: " . $e->getMessage());
        }
    }

    public function testSingleValidatorError(): void
    {
        $validator = new AccountValidation(new SingleValidator());

        try {
            $amount = 51;
            $validator->collectErrors($amount);
            self::fail("Validation should have thrown an exception.");
        } catch (AccountValidationException $e) {
            self::assertSame('Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!', $e->getMessage());
        }
    }

    public function testHourValidatorError(): void
    {
        $validator = new AccountValidation(new HourValidator(), new DayValidator(), new SingleValidator());
        $amount = 101.0;

        try {
            $validator->collectErrors($amount);
        } catch (AccountValidationException $e) {
            self::assertSame('Stündliches Einzahlungslimit von 100€ überschritten!', $e->getMessage());
        }
    }
}
