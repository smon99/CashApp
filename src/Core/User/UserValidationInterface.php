<?php declare(strict_types=1);

namespace App\Core\User;

interface UserValidationInterface
{
    public function validate($userDTO);
}