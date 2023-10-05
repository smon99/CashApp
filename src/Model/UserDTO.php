<?php declare(strict_types=1);

namespace App\Model;

class UserDTO
{
    public int $userID = 1;
    public string $username = '';
    public string $email = '';
    public string $password = '';
}