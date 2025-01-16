<?php

namespace App\Entity;

use App\Entity\AbstractEntity;

class User extends AbstractEntity
{
    private string $email;
    private string $password;
    private string $firstName;
    private string $lastName;

    public function __construct(?int $id, string $email, string $password, string $firstName, string $lastName)
    {
        parent::__construct($id);
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}


