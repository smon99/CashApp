<?php declare(strict_types=1);

namespace Test\Core;

use App\Core\User\EmptyFieldValidator;
use PHPUnit\Framework\TestCase;
use App\Core\UserValidation;
use App\Core\User\EMailValidator;
use App\Core\User\PasswordValidator;
use App\Core\User\UserDuplicationValidator;
use App\Model\UserDTO;

class UserValidationTest extends TestCase
{
    public function testUserValidationTrue(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'eMail@eMail.de';
        $password = 'Passwort123#';

        $userDTO->user = $user;
        $userDTO->eMail = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(new UserDuplicationValidator(), new PasswordValidator(), new EMailValidator());

        self::assertTrue($validation->collectErrors($userDTO));
    }

    public function testUserValidationEMailFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'eMaileMail';
        $password = 'Passwort123#';

        $userDTO->user = $user;
        $userDTO->eMail = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(new UserDuplicationValidator(), new PasswordValidator(), new EMailValidator());

        self::assertSame('Bitte gültige eMail eingeben!', $validation->collectErrors($userDTO));
    }

    public function testUserValidationDulicationEMailFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'Test@Test.de';
        $password = 'Passwort123#';

        $userDTO->user = $user;
        $userDTO->eMail = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(new UserDuplicationValidator(), new PasswordValidator(), new EMailValidator());

        self::assertSame('Fehler eMail bereits vergeben!', $validation->collectErrors($userDTO));
    }

    public function testUserValidationDuplicationUserFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Test';
        $eMail = 'Email@Email.de';
        $password = 'Passwort123#';

        $userDTO->user = $user;
        $userDTO->eMail = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(new UserDuplicationValidator(), new PasswordValidator(), new EMailValidator());

        self::assertSame('Fehler Name bereits vergeben!', $validation->collectErrors($userDTO));
    }

    public function testValidationPasswordFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'eMail@eMail.de';
        $password = 'assword';

        $userDTO->user = $user;
        $userDTO->eMail = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(new UserDuplicationValidator(), new PasswordValidator(), new EMailValidator());

        self::assertSame('Passwort Anforderungen nicht erfüllt', $validation->collectErrors($userDTO));
    }

    public function testValidationEmptyFieldTrue(): void
    {
        $userDTO = new UserDTO();

        $user = '';
        $eMail = '';
        $password = '';

        $userDTO->user = $user;
        $userDTO->eMail = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(new EmptyFieldValidator());

        self::assertSame('Alle Felder müssen ausgefüllt sein!', $validation->collectErrors($userDTO));
    }
}