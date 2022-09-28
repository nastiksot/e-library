<?php

declare(strict_types=1);

namespace App\Service\Mail\MailReplacement;

class ForgotPasswordMailReplacement extends AbstractMailReplacement
{
    public const REPLACEMENTS = [
        self::REPLACE_EMAIL,
        self::REPLACE_PROFILE_LINK,
        self::REPLACE_RESET_PASSWORD_LINK,
    ];
}
