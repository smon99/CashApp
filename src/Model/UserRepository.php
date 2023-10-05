<?php declare(strict_types=1);

namespace App\Model;

class UserRepository
{
    public function __construct(private UserMapper $userMapper, private string $path = __DIR__ . '/user.json')
    {
    }

    public function findByUsername(string $userCheck): ?UserDTO
    {
        $userDTOList = $this->getUserDTOList();

        foreach ($userDTOList as $userDTO) {
            if ($userDTO->user === $userCheck) {
                return $userDTO;
            }
        }

        return null;
    }

    public function findByMail(string $mailCheck): ?UserDTO
    {
        $userDTOList = $this->getUserDTOList();

        foreach ($userDTOList as $userDTO) {
            if ($userDTO->email === $mailCheck) {
                return $userDTO;
            }
        }

        return null;
    }

    private function getUserDTOList(): array
    {
        $jsonString = file_get_contents($this->path);
        return $this->userMapper->jsonToDTO($jsonString);
    }
}
