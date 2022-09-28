<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Contracts\Entity\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserEntityListener implements EntityListenerInterface
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory
    ) {
    }

    public function prePersist(UserInterface $user): void
    {
        $this->updateUserPassword($user);
    }

    public function preUpdate(UserInterface $user): void
    {
        $this->updateUserPassword($user);
    }

    protected function updateUserPassword(UserInterface $user): void
    {
        $plainPassword = $user->getPlainPassword();

        if (null !== $plainPassword) {
            $hasher   = $this->passwordHasherFactory->getPasswordHasher($user);
            $password = $hasher->hash($plainPassword);
            $user->setPassword($password);
        }
    }
}
