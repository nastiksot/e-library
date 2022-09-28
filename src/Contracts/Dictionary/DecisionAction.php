<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static DecisionAction REPLACE_MAIN()
 * @method static DecisionAction DELETE_MAIN()
 * @method static DecisionAction KEEP_MAIN()
 */
class DecisionAction extends Enum
{
    private const REPLACE_MAIN = 'replace_main';
    private const DELETE_MAIN  = 'delete_main';
    private const KEEP_MAIN    = 'keep_main';
}
