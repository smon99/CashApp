<?php declare(strict_types=1);

namespace App\Core\User;

use App\Model\UserDTO;

class PasswordValidator implements UserValidationInterface
{
    public function validate(UserDTO $userDTO)
    {
        $uppercase = preg_match('@[A-Z]@', $userDTO->password);
        $lowercase = preg_match('@[a-z]@', $userDTO->password);
        $number = preg_match('@[0-9]@', $userDTO->password);
        $specialChar = preg_match('@[^\w]@', $userDTO->password);
        $minLength = 6;

        if (!($uppercase && $lowercase && $number && $specialChar && strlen($userDTO->password) >= $minLength)) {
            throw new ValidationException('Passwort Anforderungen nicht erfüllt! ');
        }
    }
}
