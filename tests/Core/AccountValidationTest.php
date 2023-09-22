<?php declare(strict_types=1);

namespace Test\Core;

use App\Core\Account\DayValidator;
use App\Core\Account\HourValidator;
use App\Core\Account\SingleValidator;
use PHPUnit\Framework\TestCase;
use App\Core\AccountValidation;

class AccountValidationTest extends TestCase
{
    public function testDayDepositLimit(): void
    {
        $random = random_int(1, 50);
        $amount = (float)$random;

        $validator = new AccountValidation();

        self::assertTrue($validator->collectErrors($amount));
    }

    public function testCollectErrorsNotNumeric(): void
    {
        $validator = new AccountValidation();
        $amount = 'hi';

        self::assertSame($validator->collectErrors($amount), 'Bitte einen Betrag eingeben!');
    }

    public function testCollectErrorsNotTrue(): void
    {
        $validator = new AccountValidation(new DayValidator(), new HourValidator(), new SingleValidator());
        $amount = 510;
        $errors = $validator->collectErrors($amount);

        self::assertSame($errors, 'Tägliches Einzahlungslimit von 500€ überschritten!');
    }

    public function testCollectErrorsTrue(): void
    {
        $validator = new AccountValidation(new DayValidator(), new HourValidator(), new SingleValidator());

        $amount = 20;
        $errors = $validator->collectErrors($amount);

        self::assertTrue($errors);
    }

    public function testSingleValidatorError(): void
    {
        $validator = new AccountValidation(new SingleValidator());

        $amount = 51;
        $errors = $validator->collectErrors($amount);

        self::assertSame($errors, 'Bitte einen Betrag von mindestens 0.01€ und maximal 50€ eingeben!');
    }

    public function testHourValidatorError(): void
    {
        $validator = new AccountValidation(new HourValidator());

        $amount = 101;
        $errors = $validator->collectErrors($amount);

        self::assertSame($errors, 'Stündliches Einzahlungslimit von 100€ überschritten!');
    }
}