<?php

declare(strict_types=1);

namespace App\CQ\Command\User;

/**
 * @see RegisterUserHandler
 */
class RegisterUserCommand
{
    public function __construct(
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?string $email = null,
        private ?string $password = null,
    ) {
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
