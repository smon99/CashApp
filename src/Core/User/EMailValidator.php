<?php declare(strict_types=1);

namespace App\Core\User;

class EMailValidator implements UserValidationInterface
{
    public function validate($userDTO): string|bool
    {
        if (filter_var($userDTO->eMail, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return 'Bitte gültige eMail eingeben!';
    }
}