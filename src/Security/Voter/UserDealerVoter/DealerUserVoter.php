<?php

declare(strict_types=1);

namespace App\Security\Voter\UserDealerVoter;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DealerUserVoter extends AbstractDealerUserVoter
{
    public const LIST   = 'LIST';
    public const ADD    = 'ADD';
    public const VIEW   = 'VIEW';
    public const EDIT   = 'EDIT';
    public const DELETE = 'DELETE';

    private const ATTRIBUTE_CLASS  = [self::LIST, self::ADD];
    private const ATTRIBUTE_OBJECT = [self::EDIT, self::DELETE, self::VIEW];

    protected function supports(string $attribute, $subject): bool
    {
        if (!$this->isValidSubject($subject)) {
            return false;
        }

        return (User::class === $subject['object'] && in_array($attribute, self::ATTRIBUTE_CLASS, true))
                || ($subject['object'] instanceof User && in_array($attribute, self::ATTRIBUTE_OBJECT, true));
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (!$this->isBaseCheckForVoteOnAttribute('ROLE_DEALER_ADMIN', $subject, $token)) {
            return false;
        }

        // all basic requirements are ok

        if (User::class === $subject['object']
            && in_array($attribute, self::ATTRIBUTE_CLASS, true)) {
            // LIST/CREATE features is allowed for all authorized dealer users
            return true;
        }

        $authorizedUser = $token->getUser();

        if ($subject['object'] instanceof User
            && in_array($attribute, self::ATTRIBUTE_OBJECT, true)) {
            // - current user is a Dealer user
            // - s/he and authorized user belong to the same Dealer
            return $subject['object']->getDealer()
                && $subject['object']->getDealer()->getId() === $authorizedUser->getDealer()?->getId();
        }

        return false;
    }
}
