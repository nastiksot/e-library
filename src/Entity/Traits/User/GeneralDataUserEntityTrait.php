<?php

declare(strict_types=1);

namespace App\Entity\Traits\User;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use function in_array;
use function serialize;
use function unserialize;

trait GeneralDataUserEntityTrait
{
    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    protected ?string $password = null;

    /**
     * @var string|null
     *                  not mapped property
     */
    protected ?string $plainPassword = null;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected ?string $email = null;

    /**
     * @ORM\Column(name="roles", type="json", nullable=false)
     */
    protected array $roles = [];

    /**
     * @ORM\Column(name="google_authenticator_enabled", type="boolean", options={"default": 0})
     */
    protected bool $googleAuthenticatorEnabled = false;

    /**
     * @ORM\Column(name="google_authenticator_token", type="string", length=255, nullable=true)
     */
    protected ?string $googleAuthenticatorToken = null;

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getUserIdentifier(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        $this->setUpdatedAt(new DateTime());

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function getRole(): ?string
    {
        return $this->roles[0] ?? '';
    }

    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->googleAuthenticatorEnabled;
    }

    public function setGoogleAuthenticatorEnabled(bool $googleAuthenticatorEnabled): static
    {
        $this->googleAuthenticatorEnabled = $googleAuthenticatorEnabled;

        return $this;
    }

    public function getGoogleAuthenticatorToken(): ?string
    {
        return $this->googleAuthenticatorToken;
    }

    public function setGoogleAuthenticatorToken(?string $googleAuthenticatorToken): static
    {
        $this->googleAuthenticatorToken = $googleAuthenticatorToken;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function serialize(): string
    {
        return serialize(
            [
                $this->id,
                $this->email,
                $this->password,
            ]
        );
    }

    public function unserialize($data): void
    {
        [
            $this->id,
            $this->email,
            $this->password,
        ] = unserialize($data, ['allowed_classes' => [self::class]]);
    }

    public function __toString(): string
    {
        return $this->getEmail() ?? 'New User';
    }
}
