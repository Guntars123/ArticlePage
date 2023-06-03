<?php declare(strict_types=1);

namespace App\Services\User\Create;

class CreateUserRequest
{
    private string $userName;
    private string $email;
    private string $password;

    public function __construct(string $userName, string $email, string $password)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
