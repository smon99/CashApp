<?php declare(strict_types=1);

namespace App\Core\User;

use App\Model\UserDTO;
use App\Model\UserRepository;
use App\Model\UserMapper;

class UserDuplicationValidator implements UserValidationInterface
{
    public function validate(UserDTO $userDTO): void
    {
        $mapper = new UserMapper();
        $repository = new UserRepository($mapper);

        if ($repository->findByMail($userDTO->email) !== null) {
            throw new UserValidationException('Fehler eMail bereits vergeben! ');
        }
        if ($repository->findByUsername($userDTO->username) !== null) {
            throw new UserValidationException('Fehler Name bereits vergeben! ');
        }
    }
}
