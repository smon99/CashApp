<?php declare(strict_types=1);

namespace App\Model;

class UserEntityManager
{
    private string $path;

    public function __construct(?string $path = null)
    {
        if ($path === null) {
            $path = UserRepository::USER_DEFAULT_PATH;
        }

        $this->path = $path;
    }

    public function save(array $user): void
    {
        if (!file_exists($this->path)) {
            $firstUser = [$user];
            $saveUser = $firstUser;
            file_put_contents($this->path, json_encode([]));
        } else {
            $oldUser = json_decode(file_get_contents($this->path));
            $oldUser[] = $user;
            $saveUser = $oldUser;
        }

        $user_data = json_encode($saveUser, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents($this->path, $user_data, LOCK_EX);
    }
}
