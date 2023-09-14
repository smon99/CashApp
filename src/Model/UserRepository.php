<?php declare(strict_types=1);

namespace App\Model;

class UserRepository
{

    //private string $path;

    public function __construct(private string $path = __DIR__ . '/user.json') {
        /*
        if ($path === null) {
            $this->path = self::USER_DEFAULT_PATH;
        } else {
            $this->path = $path;
        }
        */
    }

    public function findByUsername(string $userCheck): ?array
    {
        $user = json_decode(file_get_contents($this->path), true);

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
        $user = json_decode(file_get_contents($this->path), true);

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
