<?php declare(strict_types=1);

namespace App\Core\User;

use App\Model\UserDTO;

class EMailValidator implements UserValidationInterface
{
    public function validate(UserDTO $userDTO)
    {
        if (!filter_var($userDTO->eMail, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Bitte gültige eMail eingeben! ');
        }
    }
}
