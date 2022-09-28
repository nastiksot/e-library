<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static DealerRequestStatus NEW()
 * @method static DealerRequestStatus ANSWERED()
 * @method static DealerRequestStatus MEETING_PLANNED()
 * @method static DealerRequestStatus CLOSED()
 */
class DealerRequestStatus extends Enum
{
    private const NEW             = 'new';
    private const ANSWERED        = 'answered';
    private const MEETING_PLANNED = 'meeting_planned';
    private const CLOSED          = 'closed';
}
