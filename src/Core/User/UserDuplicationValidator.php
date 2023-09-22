<?php declare(strict_types=1);

namespace App\Core\User;

use App\Model\UserRepository;
use App\Model\UserMapper;

class UserDuplicationValidator implements UserValidationInterface
{
    public function validate($userDTO): string|bool
    {
        $mapper = new UserMapper();
        $repository = new UserRepository($mapper);

        if ($repository->findByMail($userDTO->eMail) !== null) {
            return 'Fehler eMail bereits vergeben!';
        }
        if ($repository->findByUsername($userDTO->user) !== null) {
            return 'Fehler Name bereits vergeben!';
        }
        return true;
    }
}