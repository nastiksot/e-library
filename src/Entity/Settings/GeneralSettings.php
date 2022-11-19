<?php

declare(strict_types=1);

namespace App\Entity\Settings;

use App\Entity\AbstractEntity;
use App\Entity\Traits\PenaltyEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="settings_general",
 *     indexes={
 *          @ORM\Index(name="idx_created_at", columns={"created_at"}),
 *          @ORM\Index(name="idx_updated_at", columns={"updated_at"}),
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\Settings\GeneralSettingsRepository")
 */
class GeneralSettings extends AbstractEntity
{
    use PenaltyEntityTrait;

    /**
     * @ORM\Column(name="expire_color", type="string", length=255, nullable=true)
     */
    private ?string $expireColor = null;


    public function __toString(): string
    {
        return 'General Settings';
    }

    public function getExpireColor(): ?string
    {
        return $this->expireColor;
    }

    public function setExpireColor(?string $expireColor): self
    {
        $this->expireColor = $expireColor;

        return $this;
    }
}
