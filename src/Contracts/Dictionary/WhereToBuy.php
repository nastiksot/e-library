<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static WhereToBuy ONLINE_AND_RETAIL()
 * @method static WhereToBuy ONLINE()
 * @method static WhereToBuy RETAIL()
 */
class WhereToBuy extends Enum
{
    private const ONLINE_AND_RETAIL = 'online_and_retail';
    private const ONLINE            = 'online';
    private const RETAIL            = 'retail';
}
