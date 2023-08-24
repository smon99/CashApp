<?php declare(strict_types=1);

namespace Model;

class UserRepository
{
    public function findByUsername(string $userCheck): ?array
    {
        $user = json_decode(file_get_contents(__DIR__ . '/../Model/user.json'), true);

        foreach ($user as $userRun) {
            if ($userRun["user"] === $userCheck) {
                $userRequest["user"] = $userRun["user"];
                $userRequest["eMail"] = $userRun["eMail"];
                $userRequest["password"] = $userRun["password"];
                return $userRequest;
            }
        }
        return null;
    }

    public function findByMail(string $mailCheck): ?array
    {
        $user = json_decode(file_get_contents(__DIR__ . '/../Model/user.json'), true);

        foreach ($user as $userRun) {
            if ($userRun["eMail"] === $mailCheck) {
                $mailRequest["user"] = $userRun["user"];
                $mailRequest["eMail"] = $userRun["eMail"];
                $mailRequest["password"] = $userRun["password"];
                return $mailRequest;
            }
        }
        return null;
    }
}