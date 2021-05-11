<?php

declare(strict_types=1);

namespace App\Entity\Contracts;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends EntityInterface, BaseUserInterface, \Serializable
{
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN       = 'ROLE_ADMIN';
    public const ROLE_USER        = 'ROLE_USER';
    public const ROLE_AUTHOR      = 'ROLE_AUTHOR';
    public const ROLE_READER      = 'ROLE_READER';
    public const ROLE_librarian   = 'ROLE_librarian';


    public function isActive(): bool;

    public function setPlainPassword(string $password);

    public function getPlainPassword(): ?string;

    public function setPassword(string $password);

    public function hasRole(string $role): bool;

    public function getEmail(): ?string;
}
