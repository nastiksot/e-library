<?php

declare(strict_types=1);

namespace App\Security\Voter\UserDealerVoter;

use App\Entity\DealerRequest\DealerRequest;
use App\Entity\DealerRequest\DealerRequestComment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserDealerRequestVoter extends AbstractDealerUserVoter
{
    public const DEALER_REQUEST_LIST = 'DEALER_REQUEST_LIST';

    public const DEALER_REQUEST_DELETE          = 'DEALER_REQUEST_DELETE';
    public const DEALER_REQUEST_STATUS_UPDATE   = 'DEALER_REQUEST_STATUS_UPDATE';
    public const DEALER_REQUEST_ARCHIVED_UPDATE = 'DEALER_REQUEST_ARCHIVED_UPDATE';
    public const DEALER_REQUEST_COMMENT_LIST    = 'DEALER_REQUEST_COMMENT_LIST';
    public const DEALER_REQUEST_COMMENT_ADD     = 'DEALER_REQUEST_COMMENT_ADD';

    public const DEALER_REQUEST_COMMENT_UPDATE = 'DEALER_REQUEST_COMMENT_UPDATE';
    public const DEALER_REQUEST_COMMENT_DELETE = 'DEALER_REQUEST_COMMENT_DELETE';

    private const ATTRIBUTE_CLASS          = [self::DEALER_REQUEST_LIST];
    private const ATTRIBUTE_REQUEST_OBJECT = [
        self::DEALER_REQUEST_DELETE,
        self::DEALER_REQUEST_STATUS_UPDATE,
        self::DEALER_REQUEST_ARCHIVED_UPDATE,
        self::DEALER_REQUEST_COMMENT_LIST,
        self::DEALER_REQUEST_COMMENT_ADD,
    ];
    private const ATTRIBUTE_REQUEST_COMMENT_OBJECT = [
        self::DEALER_REQUEST_COMMENT_UPDATE,
        self::DEALER_REQUEST_COMMENT_DELETE,
    ];

    protected function supports(string $attribute, $subject): bool
    {
        if (!$this->isValidSubject($subject)) {
            return false;
        }

        return (DealerRequest::class === $subject['object'] && in_array($attribute, self::ATTRIBUTE_CLASS, true))
            || ($subject['object'] instanceof DealerRequest && in_array($attribute, self::ATTRIBUTE_REQUEST_OBJECT, true))
            || ($subject['object'] instanceof DealerRequestComment && in_array($attribute, self::ATTRIBUTE_REQUEST_COMMENT_OBJECT, true));
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if (!$this->isBaseCheckForVoteOnAttribute('ROLE_DEALER_EMPLOYEE', $subject, $token)) {
            return false;
        }

        // all basic requirements are ok

        if (DealerRequest::class === $subject['object']
            && in_array($attribute, self::ATTRIBUTE_CLASS, true)) {
            // LIST feature is allowed for all authorized dealer users that belong to current Dealer
            return true;
        }

        $authorizedUser = $token->getUser();

        if ($subject['object'] instanceof DealerRequest
            && in_array($attribute, self::ATTRIBUTE_REQUEST_OBJECT, true)) {
            // current Dealer request and authorized user should belong to the same Dealer
            return $subject['object']->getDealer()->getId() === $authorizedUser->getDealer()?->getId();
        }

        if ($subject['object'] instanceof DealerRequestComment
            && in_array($attribute, self::ATTRIBUTE_REQUEST_COMMENT_OBJECT, true)) {
            // Dealer request that has current comment and authorized user should belong to the same Dealer
            return $subject['object']->getDealerRequest()?->getDealer()?->getId() === $authorizedUser->getDealer()?->getId();
        }

        return false;
    }
}
