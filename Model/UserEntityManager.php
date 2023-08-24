<?php declare(strict_types=1);

namespace Model;

class UserEntityManager
{
    public function save($user): void
    {
        if (!file_exists(__DIR__ . '/../Model/user.json')) {
            $firstUser = [$user];
            $saveUser = $firstUser;
            file_put_contents(__DIR__ . '/../Model/user.json', json_encode([]));
        } else {
            $oldUser = json_decode(file_get_contents(__DIR__ . '/../Model/user.json'));
            $oldUser[] = $user;
            $saveUser = $oldUser;
        }
        $user_data = json_encode($saveUser, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        file_put_contents(__DIR__ . '/../Model/user.json', $user_data, LOCK_EX);
    }
}