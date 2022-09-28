<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static FileMimeType IMAGE()
 * @method static FileMimeType VIDEO()
 * @method static FileMimeType ANIMATED_GIF()
 */
class FileMimeType extends Enum
{
    private const IMAGE        = 'image';
    private const VIDEO        = 'video';
    private const ANIMATED_GIF = 'animated_gif';
}
