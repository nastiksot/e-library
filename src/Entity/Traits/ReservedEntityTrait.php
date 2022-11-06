<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ReservedEntityTrait
{
    /**
     * @ORM\Column(name="reserved", type="integer", nullable=false, options={"default":"0", "unsigned": true})
     */
    protected int $reserved = 0;

    public function getReserved(): int
    {
        return $this->reserved;
    }

    public function setReserved(int $reserved): self
    {
        $this->reserved = $reserved;

        return $this;
    }
}
