<?php declare(strict_types=1);

namespace Test\Core;

use App\Core\UserValidation;
use App\Core\User\EmptyFieldValidator;
use App\Core\User\EMailValidator;
use App\Core\User\PasswordValidator;
use App\Core\User\UserDuplicationValidator;
use App\Core\User\UserValidationException;
use App\Model\SqlConnector;
use App\Model\UserDTO;
use App\Model\UserMapper;
use PHPUnit\Framework\TestCase;
use App\Model\UserEntityManager;

class UserValidationTest extends TestCase
{
    public function testUserValidationTrue(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'eMail@eMail.de';
        $password = 'Passwort123#';

        $userDTO->username = $user;
        $userDTO->email = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(
            new UserDuplicationValidator(),
            new PasswordValidator(),
            new EMailValidator()
        );

        $this->expectNotToPerformAssertions();

        $validation->collectErrors($userDTO);
    }

    public function testUserValidationEMailFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'eMaileMail';
        $password = 'Passwort123#';

        $userDTO->username = $user;
        $userDTO->email = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(
            new UserDuplicationValidator(),
            new PasswordValidator(),
            new EMailValidator()
        );

        $this->expectException(UserValidationException::class);
        $this->expectExceptionMessage('Bitte gültige eMail eingeben!');

        $validation->collectErrors($userDTO);
    }

    public function testUserValidationDuplicationEMailFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'Simon@Simon.de';
        $password = 'Passwort123#';

        $userDTO->username = $user;
        $userDTO->email = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(
            new UserDuplicationValidator(),
            new PasswordValidator(),
            new EMailValidator()
        );

        $this->expectException(UserValidationException::class);
        $this->expectExceptionMessage('Fehler eMail bereits vergeben!');

        $validation->collectErrors($userDTO);
    }

    public function testUserValidationDuplicationUserFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Simon';
        $eMail = 'Email@Email.de';
        $password = 'Passwort123#';

        $userDTO->username = $user;
        $userDTO->email = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(
            new UserDuplicationValidator(),
            new PasswordValidator(),
            new EMailValidator()
        );

        $this->expectException(UserValidationException::class);
        $this->expectExceptionMessage('Fehler Name bereits vergeben!');

        $validation->collectErrors($userDTO);
    }

    public function testValidationPasswordFalse(): void
    {
        $userDTO = new UserDTO();

        $user = 'Benutzer';
        $eMail = 'eMail@eMail.de';
        $password = 'assword'; // Invalid password

        $userDTO->username = $user;
        $userDTO->email = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(
            new UserDuplicationValidator(),
            new PasswordValidator(),
            new EMailValidator()
        );

        $this->expectException(UserValidationException::class);
        $this->expectExceptionMessage('Passwort Anforderungen nicht erfüllt');

        $validation->collectErrors($userDTO);
    }

    public function testValidationEmptyFieldTrue(): void
    {
        $userDTO = new UserDTO();

        $user = '';
        $eMail = '';
        $password = '';

        $userDTO->username = $user;
        $userDTO->email = $eMail;
        $userDTO->password = $password;

        $validation = new UserValidation(
            new EmptyFieldValidator()
        );

        $this->expectException(UserValidationException::class);
        $this->expectExceptionMessage('Alle Felder müssen ausgefüllt sein!');

        $validation->collectErrors($userDTO);
    }
}
