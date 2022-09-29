<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use Serializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

interface UserInterface extends EntityInterface, PasswordAuthenticatedUserInterface, BaseUserInterface, Serializable
{
    public const ROLE_USER        = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN       = 'ROLE_ADMIN';
    public const ROLE_EDITOR      = 'ROLE_EDITOR';
    public const ROLE_READER      = 'ROLE_READER';
    public const ROLE_LIBRARIAN   = 'ROLE_LIBRARIAN';

    public const ALL_ROLES = [
        self::ROLE_USER,
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN,
        self::ROLE_EDITOR,
        self::ROLE_READER,
        self::ROLE_LIBRARIAN,
    ];

    public function setPlainPassword(string $password): static;

    public function getPlainPassword(): ?string;

    public function setPassword(string $password): static;

    public function isGoogleAuthenticatorEnabled(): bool;

    public function getGoogleAuthenticatorToken(): ?string;

    public function setGoogleAuthenticatorToken(?string $googleAuthenticatorToken): static;

    public function isActive(): bool;

    public function hasRole(string $role): bool;

    public function getRole(): ?string;

    public function getRoles(): array;

    public function setRoles(array $roles): static;

    public function getEmail(): ?string;
}
