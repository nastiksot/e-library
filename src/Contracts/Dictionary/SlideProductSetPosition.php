<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static SlideProductSetPosition CENTER()
 * @method static SlideProductSetPosition LEFT()
 * @method static SlideProductSetPosition RIGHT()
 */
class SlideProductSetPosition extends Enum
{
    private const CENTER = 'center';
    private const LEFT   = 'left';
    private const RIGHT  = 'right';
}
