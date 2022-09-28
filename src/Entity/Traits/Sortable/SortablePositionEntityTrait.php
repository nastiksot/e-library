<?php

declare(strict_types=1);

namespace App\Entity\Traits\Sortable;

use Doctrine\ORM\Mapping as ORM;

trait SortablePositionEntityTrait
{
    /**
     * @Gedmo\Mapping\Annotation\SortablePosition()
     * @ORM\Column(name="position", type="integer", nullable=false, options={"default":"0"})
     */
    protected int $position = 0;

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}
