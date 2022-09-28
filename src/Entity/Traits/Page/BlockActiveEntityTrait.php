<?php

declare(strict_types=1);

namespace App\Entity\Traits\Page;

use Doctrine\ORM\Mapping as ORM;

trait BlockActiveEntityTrait
{
    /**
     * @ORM\Column(name="name1block_active", type="boolean", nullable=false, options={"default": 0})
     */
    protected bool $name1BlockActive = false;

    public function isName1BlockActive(): bool
    {
        return $this->name1BlockActive;
    }

    public function setName1BlockActive(bool $name1BlockActive): self
    {
        $this->name1BlockActive = $name1BlockActive;

        return $this;
    }
}
