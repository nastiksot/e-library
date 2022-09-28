<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TipEntityTrait
{
    /**
     * @ORM\Column(name="tip", type="string", length=255, nullable=true)
     */
    protected ?string $tip = null;

    public function getTip(): ?string
    {
        return $this->tip;
    }

    public function setTip(?string $tip): self
    {
        $this->tip = $tip;

        return $this;
    }
}
