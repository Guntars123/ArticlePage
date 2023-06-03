<?php declare(strict_types=1);

namespace App\Models;

class User
{
    private string $userName;
    private string $email;
    private string $password;
    private ?int $id;

    public function __construct
    (
        string    $userName,
        string    $email,
        string    $password,
        int       $id = null
    )
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function update(array $attributes): void
    {
        foreach ($attributes as $attribute => $value)
        {
            $this->{$attribute} = $value;
        }
    }
}
