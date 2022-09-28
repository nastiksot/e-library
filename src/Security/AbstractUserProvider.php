<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use function get_class;
use function is_subclass_of;
use function sprintf;

abstract class AbstractUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->getOneByIdentifier($identifier);

        if (!$user instanceof UserInterface) {
            $e = new UserNotFoundException(sprintf('User with id `%s` not found.', $identifier));
            $e->setUserIdentifier($identifier);

            throw $e;
        }

        if (!$user->isActive()) {
            throw new LockedException(sprintf('User with id `%s`is disabled.', $identifier));
        }

        return $user;
    }

    /**
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->loadUserByIdentifier($username);
    }

    /**
     * @throws UserNotFoundException    if the user is not found
     * @throws UnsupportedUserException if the user is not supported
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        $refreshedUser = $this->userRepository->getOneById($user->getId());

        if (!$refreshedUser instanceof UserInterface) {
            $e = new UserNotFoundException(sprintf('User with id `%s` not found.', $user->getUserIdentifier()));
            $e->setUserIdentifier($user->getUserIdentifier());

            throw $e;
        }

        return $refreshedUser;
    }

    public function upgradePassword(\App\Contracts\Entity\UserInterface $user, string $newHashedPassword): void
    {
        $user->setPassword($newHashedPassword);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }
}
