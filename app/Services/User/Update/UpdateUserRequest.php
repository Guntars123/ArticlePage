<?php declare(strict_types=1);

class UpdateUserRequest
{
    private int $id;
    private string $userName;
    private string $password;

    public function __construct(int $id, string $userName, string $password)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
