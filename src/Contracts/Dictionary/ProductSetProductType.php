<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static ProductSetProductType REGULAR()
 * @method static ProductSetProductType OPTIONAL()
 */
class ProductSetProductType extends Enum
{
    private const REGULAR  = 'regular';
    private const OPTIONAL = 'optional';
}
