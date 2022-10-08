<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static OrderStatus OPEN()
 * @method static OrderStatus DONE()
 * @method static OrderStatus CANCEL()
 */
class OrderStatus extends Enum
{
    private const OPEN   = 'open';
    private const DONE   = 'done';
    private const CANCEL = 'cancel';
}
