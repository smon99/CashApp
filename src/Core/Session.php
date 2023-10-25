<?php declare(strict_types=1);

namespace App\Core;

use App\Model\UserDTO;

class Session
{
    public function loginStatus(): bool
    {
        return !empty($_SESSION["username"]);
    }

    public function getUserName(): string
    {
        return $_SESSION["username"] ?? '';
    }

    public function getUserID(): ?int
    {
        return $_SESSION["userID"];
    }

    public function loginUser(UserDTO $userDTO, string $password): void
    {
        if (password_verify($password, $userDTO->password)) {
            $_SESSION["username"] = $userDTO->username;
            $_SESSION["userID"] = $userDTO->userID;
            $_SESSION["loginStatus"] = true;
        }
    }

    public function logout(): void
    {
        unset($_SESSION["username"], $_SESSION["userID"], $_SESSION["loginStatus"]);
    }
}