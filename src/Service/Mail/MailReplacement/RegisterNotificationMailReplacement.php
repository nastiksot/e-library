<?php

declare(strict_types=1);

namespace App\Service\Mail\MailReplacement;

class RegisterNotificationMailReplacement extends AbstractMailReplacement
{
    public const REPLACEMENTS = [
        self::REPLACE_EMAIL,
        self::REPLACE_ADMIN_FILTER_LINK,
    ];
}
