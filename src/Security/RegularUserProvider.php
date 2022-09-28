<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use App\Contracts\Entity\UserInterface;
use function sprintf;

class RegularUserProvider extends AbstractUserProvider
{
    /**
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        /** @var User $user */
        $user = parent::loadUserByIdentifier($identifier);

        $allowedRoles   = UserInterface::AVAILABLE_DEALER_ROLES;
        $allowedRoles[] = UserInterface::ROLE_USER;

        $isAllowedRole = !empty(array_intersect($allowedRoles, $user->getRoles()));

        if (!$isAllowedRole) {
            $e = new UserNotFoundException(sprintf('User with id `%s` not found.', $identifier));
            $e->setUserIdentifier($identifier);

            throw $e;
        }

        return $user;
    }
}
