<?php declare(strict_types=1);

namespace App\Core;

use App\Core\User\UserValidationInterface;
use App\Core\User\ValidationException;
use App\Model\UserDTO;

class UserValidation
{
    private array $validationCollection;

    public function __construct(UserValidationInterface ...$validations)
    {
        $this->validationCollection = $validations;
    }

    public function collectErrors(UserDTO $userDTO): void
    {
        $errors = [];

        foreach ($this->validationCollection as $validator) {
            try {
                $validator->validate($userDTO);
            } catch (ValidationException $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            throw new ValidationException(implode(' ', $errors));
        }
    }
}
