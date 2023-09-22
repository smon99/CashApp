<?php declare(strict_types=1);

namespace App\Core\User;

class EmptyFieldValidator implements UserValidationInterface
{
    public function validate($userDTO): string|bool
    {
        if (empty($userDTO->user) || empty($userDTO->eMail) || empty($userDTO->password)) {
            return 'Alle Felder müssen ausgefüllt sein!';
        }
        return true;
    }
}