<?php declare(strict_types=1);

namespace App\Core\User;

use App\Model\UserDTO;

interface UserValidationInterface
{
    /**
     * @param UserDTO $userDTO
     *
     * @throws ValidationException If validation criteria is not matched.
     */
    public function validate(UserDTO $userDTO);
}