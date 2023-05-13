<?php declare(strict_types=1);

namespace App\Models;

class User
{
    private int $id;
    private string $name;
    private string $userName;
    private string $email;
    private \stdClass $address;
    private string $phone;
    private string $website;
    private \stdClass $company;


    public function __construct
    (
        int $id,
        string $name,
        string $userName,
        string $email,
        \stdClass $address,
        string $phone,
        string $website,
        \stdClass $company
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;
        $this->email = $email;
        $this->address = $address;
        $this->phone = $phone;
        $this->website = $website;
        $this->company = $company;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAddress(): \stdClass
    {
        return $this->address;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function getCompany(): \stdClass
    {
        return $this->company;
    }
}
