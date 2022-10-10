<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static ReadingType SUBSCRIPTION()
 * @method static ReadingType READING_HALL()
 */
class ReadingType extends Enum
{
    private const SUBSCRIPTION = 'subscription';
    private const READING_HALL = 'reading-hall';
}
