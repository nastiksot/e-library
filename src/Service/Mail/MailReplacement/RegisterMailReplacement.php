<?php

declare(strict_types=1);

namespace App\Service\Mail\MailReplacement;

use App\Entity\User\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\UuidV4;
use function array_keys;
use function array_values;
use function str_replace;

class RegisterMailReplacement extends AbstractMailReplacement
{
    public const WISH_LIST_LINK        = '%%wish_list_link%%';
    public const CONFIRM_REGISTER_LINK = '%%confirm_register_link%%';

    public const REPLACEMENTS = [
        self::REPLACE_EMAIL,
        self::WISH_LIST_LINK,
        self::CONFIRM_REGISTER_LINK,
    ];

    final public function handleReplacements(?UuidV4 $uid, User $user, ?string $content): ?string
    {
        $content      = $this->handleUserReplacements($user, $content);
        $replacements = [
            static::CONFIRM_REGISTER_LINK => $this->resolveReplaceConfirmRegisterLink($user),
        ];

        if ($uid) {
            $replacements[static::WISH_LIST_LINK] = $this->resolveReplaceWishListLink($uid, $user->getLocale());
        }

        return $content ? str_replace(array_keys($replacements), array_values($replacements), $content) : null;
    }

    private function resolveReplaceWishListLink(UuidV4 $uid, string $locale): string
    {
        return $this->router->generate(
            'web.wishlist.details',
            ['_locale' => $locale, 'wishListUid' => $uid->toRfc4122()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function resolveReplaceConfirmRegisterLink(User $user): string
    {
        return $this->router->generate(
            'web.auth.register_confirm',
            ['_locale' => $user->getLocale(), 'token' => $user->getRegisterConfirmToken()?->toRfc4122()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
