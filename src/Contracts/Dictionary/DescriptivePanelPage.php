<?php

declare(strict_types=1);

namespace App\Contracts\Dictionary;

use MyCLabs\Enum\Enum;

/**
 * @method static DescriptivePanelPage HOME_PLANNER()
 * @method static DescriptivePanelPage DAY_WITH_SOMFY()
 * @method static DescriptivePanelPage WISH_LIST()
 */
class DescriptivePanelPage extends Enum
{
    private const HOME_PLANNER   = 'home-planer';
    private const DAY_WITH_SOMFY = 'day-with-somfy';
    private const WISH_LIST      = 'wish-list';
}
