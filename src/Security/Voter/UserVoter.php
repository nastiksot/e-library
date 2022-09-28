<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const DELETE = 'DELETE';

    public function __construct(
        private Security $security
    ) {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $subject instanceof User && self::DELETE === $attribute;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            return false;
        }

        $authorizedUser = $token->getUser();

        if (self::DELETE === $attribute &&
            $subject instanceof User &&
            $authorizedUser instanceof User &&
            $authorizedUser->getId() === $subject->getId()) {
            return true;
        }

        return false;
    }
}
