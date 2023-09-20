<?php declare(strict_types=1);

namespace App\Model;

class UserEntityManager
{
    private UserMapper $userMapper;
    private string $path;

    public function __construct(UserMapper $userMapper, ?string $path = null)
    {
        $this->userMapper = $userMapper;

        if ($path === null) {
            $path = __DIR__ . '/user.json';
        }

        $this->path = $path;
    }

    public function save(UserDTO $userDTO): void
    {
        $userDTOList = $this->getUserDTOList();

        $userDTOList[] = $userDTO;

        $user_data = $this->userMapper->jsonFromDTO($userDTOList);

        file_put_contents($this->path, $user_data, LOCK_EX);
    }

    private function getUserDTOList(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }

        $jsonString = file_get_contents($this->path);

        return $this->userMapper->jsonToDTO($jsonString);
    }
}
