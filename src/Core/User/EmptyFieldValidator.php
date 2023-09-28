<?php declare(strict_types=1);

namespace App\Core\User;

use App\Model\UserDTO;

class EmptyFieldValidator implements UserValidationInterface
{
    public function validate(UserDTO $userDTO)
    {
        if (empty($userDTO->user) || empty($userDTO->eMail) || empty($userDTO->password)) {
            throw new UserValidationException('Alle Felder müssen ausgefüllt sein! ');
        }
    }
}
