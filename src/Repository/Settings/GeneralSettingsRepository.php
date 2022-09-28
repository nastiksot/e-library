<?php

declare(strict_types=1);

namespace App\Repository\Settings;

use App\Entity\Settings\GeneralSettings;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GeneralSettings getSettings()
 */
class GeneralSettingsRepository extends AbstractSettingsRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeneralSettings::class);
    }
}
