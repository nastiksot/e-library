<?php

declare(strict_types=1);

namespace App\Entity\Settings;

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="settings_general")
 * @ORM\Entity(repositoryClass="App\Repository\Settings\GeneralSettingsRepository")
 */
class GeneralSettings extends AbstractEntity
{
    public function __toString(): string
    {
        return 'General Settings';
    }
}
