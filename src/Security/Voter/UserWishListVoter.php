<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User\User;
use App\Entity\WishList\WishList;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserWishListVoter extends Voter
{
    public const EDIT = 'EDIT';

    public function __construct(
        private Security $security
    ) {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $subject instanceof WishList && self::EDIT === $attribute;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (!$this->security->isGranted('ROLE_USER')) {
            return false;
        }

        $authorizedUser = $token->getUser();

        if (!$authorizedUser instanceof User) {
            return false;
        }

        if ($subject instanceof WishList
            && self::EDIT === $attribute
            && $authorizedUser->getId() === $subject->getUser()?->getId()) {
            // this wishList should belong to Authorized user
            return true;
        }

        return false;
    }
}
