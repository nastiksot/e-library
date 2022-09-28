<?php

declare(strict_types=1);

namespace App\Service\Mail\MailReplacement;

use App\Entity\DealerRequest\DealerRequest;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\UuidV4;
use function array_keys;
use function array_values;
use function str_replace;

class DealerRequestMailReplacement extends AbstractMailReplacement
{
    // customer replacements
    public const CUSTOMER_NAME    = '%%customer_name%%';
    public const CUSTOMER_EMAIL   = '%%customer_email%%';
    public const CUSTOMER_PHONE   = '%%customer_phone%%';
    public const CUSTOMER_ADDRESS = '%%customer_address%%';
    public const CUSTOMER_MESSAGE = '%%customer_message%%';

    // dealer replacements
    public const DEALER_NAME           = '%%dealer_name%%';
    public const DEALER_LINK           = '%%dealer_link%%';
    public const DEALER_WISH_LIST_LINK = '%%dealer_wish_list_link%%';

    public const REPLACEMENTS = [
        self::CUSTOMER_NAME,
        self::CUSTOMER_EMAIL,
        self::CUSTOMER_PHONE,
        self::CUSTOMER_ADDRESS,
        self::CUSTOMER_MESSAGE,
        self::DEALER_NAME,
        self::DEALER_LINK,
        self::DEALER_WISH_LIST_LINK,
    ];

    final public function handleReplacements(DealerRequest $dealerRequest, ?string $content): ?string
    {
        $locale             = $dealerRequest->getLocale();
        $dealer             = $dealerRequest->getDealer();
        $dealerLink         = $this->resolveReplaceDealerLink($dealer->getSlug(), $locale);
        $dealerWishListUid  = $dealerRequest->getWishList()?->getUid();
        $dealerWishListLink = $this->resolveReplaceDealerWishListLink($dealer->getSlug(), $dealerWishListUid, $locale);
        $replacements       = [
            // customer replacements
            static::CUSTOMER_NAME    => $dealerRequest->getContactName(),
            static::CUSTOMER_EMAIL   => $dealerRequest->getEmail(),
            static::CUSTOMER_PHONE   => $dealerRequest->getPhone(),
            static::CUSTOMER_ADDRESS => $dealerRequest->getAddress(),
            static::CUSTOMER_MESSAGE => $dealerRequest->getMessage(),
            // dealer replacements
            static::DEALER_NAME           => $dealerRequest->getDealer()->getTitle(),
            static::DEALER_LINK           => $dealerLink,
            static::DEALER_WISH_LIST_LINK => $dealerWishListLink,
        ];

        return $content ? str_replace(array_keys($replacements), array_values($replacements), $content) : null;
    }

    private function resolveReplaceDealerLink(
        string $dealerSlug,
        ?string $locale
    ): string {
        return $this->router->generate(
            'web.dealer.homepage',
            ['_locale' => $locale, 'dealerSlug' => $dealerSlug],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function resolveReplaceDealerWishListLink(
        string $dealerSlug,
        ?UuidV4 $wishListUid,
        ?string $locale
    ): ?string {
        return $wishListUid
            ? $this->router->generate(
                'web.dealer.wishlist.details',
                ['_locale' => $locale, 'dealerSlug' => $dealerSlug, 'wishListUid' => $wishListUid],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
            : null;
    }
}
