<?php declare(strict_types=1);

namespace App\Core;

use App\Core\User\UserValidationInterface;
use App\Model\UserDTO;

class UserValidation
{
    private array $validationCollection;

    public function __construct(
        UserValidationInterface ...$validations
    )
    {
        $this->validationCollection = $validations;
    }

    public function collectErrors(UserDTO $userDTO): string|bool
    {
        foreach ($this->validationCollection as $validator) {     //array mit allen Fehlern soll gesammelt werden, alle Fehler sollen ausgegeben werden
            $validatorResult = $validator->validate($userDTO);
            if ($validatorResult !== true) {
                return $validatorResult;
            }
        }
        return true;
    }
}