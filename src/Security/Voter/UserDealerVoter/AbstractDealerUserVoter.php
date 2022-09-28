<?php

declare(strict_types=1);

namespace App\Security\Voter\UserDealerVoter;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

abstract class AbstractDealerUserVoter extends Voter
{
    public function __construct(
        private Security $security
    ) {
    }

    protected function isValidSubject(mixed $subject): bool
    {
        return is_array($subject) &&
            !empty($subject['object']) &&
            !empty($subject['dealerIdBasedOnUid']);
    }

    protected function isBaseCheckForVoteOnAttribute(string $requiredRole, $subject, TokenInterface $token)
    {
        if (!$this->isValidSubject($subject)) {
            return false;
        }

        if (!$this->security->isGranted($requiredRole)) {
            return false;
        }

        $authorizedUser = $token->getUser();

        if (!$authorizedUser instanceof User || !$authorizedUser->getDealer()) {
            // authorized user must be a Dealer user
            return false;
        }

        if ($authorizedUser->getDealer()->getId() !== $subject['dealerIdBasedOnUid']) {
            // authorized user has access only to the data of his Dealer
            return false;
        }

        return true;
    }
}
