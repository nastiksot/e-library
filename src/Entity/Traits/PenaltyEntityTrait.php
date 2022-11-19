<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait PenaltyEntityTrait
{
    /**
     * @ORM\Column(name="penalty", type="float", nullable=true)
     */
    protected ?float $penalty = null;

    public function getPenalty(): ?float
    {
        return $this->penalty;
    }

    public function setPenalty(?float $penalty): self
    {
        $this->penalty = $penalty;

        return $this;
    }
}
