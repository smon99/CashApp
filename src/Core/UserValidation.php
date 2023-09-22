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

    public function collectErrors($userDTO): string|bool
    {
        foreach ($this->validationCollection as $validator) {
            if ($validator->validate($userDTO) !== true) {
                return $validator->validate($userDTO);
            }
        }
        return true;
    }
}