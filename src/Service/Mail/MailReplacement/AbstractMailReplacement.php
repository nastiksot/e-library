<?php

declare(strict_types=1);

namespace App\Service\Mail\MailReplacement;

use App\Entity\User\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use function array_keys;
use function array_values;
use function str_replace;

abstract class AbstractMailReplacement
{
    // the realized  replacements
    public const REPLACEMENTS = [];

    // general user's replacements
    public const REPLACE_EMAIL               = '%%email%%';
    public const REPLACE_PROFILE_LINK        = '%%profile_link%%';
    public const REPLACE_ADMIN_FILTER_LINK   = '%%admin_filter_link%%';
    public const REPLACE_RESET_PASSWORD_LINK = '%%reset_password_link%%';

    // user's replacements
    public const USER_REPLACEMENTS = [
        self::REPLACE_EMAIL,
        self::REPLACE_PROFILE_LINK,
        self::REPLACE_ADMIN_FILTER_LINK,
        self::REPLACE_RESET_PASSWORD_LINK,
    ];

    public function __construct(
        protected RouterInterface $router,
        protected TranslatorInterface $translator,
    ) {
    }

    final public function handleUserReplacements(User $user, ?string $content): ?string
    {
        $replacements     = $this->createUserReplacements($user);
        $replacementsKeys = array_keys($replacements);

        return $content ? str_replace($replacementsKeys, array_values($replacements), $content) : null;
    }

    final public function createUserReplacements(User $user): array
    {
        $replacements = [];

        foreach (static::USER_REPLACEMENTS as $replacementKey) {
            $replacementValue = match ($replacementKey) {
                static::REPLACE_EMAIL               => $user->getEmail(),
                static::REPLACE_PROFILE_LINK        => $this->resolveReplaceProfileLink($user),
                static::REPLACE_ADMIN_FILTER_LINK   => $this->resolveReplaceAdminFilterLink($user),
                static::REPLACE_RESET_PASSWORD_LINK => $this->resolveReplaceResetPasswordLink($user),
                default                             => $replacementKey,
            };

            // remember replacement
            $replacements[$replacementKey] = $replacementValue;
        }

        return $replacements;
    }

    private function resolveReplaceProfileLink(User $user): string
    {
        return $this->router->generate(
            'web.account',
            ['_locale' => $user->getLocale()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function resolveReplaceAdminFilterLink(User $user): string
    {
        return $this->router->generate(
            'admin_user_portal_list',
            ['filter' => ['id' => ['value' => $user->getId()]]],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function resolveReplaceResetPasswordLink(User $user): string
    {
        if (!$user->getForgotPasswordToken()) {
            return '';
        }

        return $this->router->generate(
            'web.auth.reset_password',
            ['_locale' => $user->getLocale(), 'token' => $user->getForgotPasswordToken()->toRfc4122()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
