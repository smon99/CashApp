<?php declare(strict_types=1);

namespace App\Core\User;

use App\Model\UserDTO;

interface UserValidationInterface
{
    public function validate(UserDTO $userDTO);
}