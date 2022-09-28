<?php

declare(strict_types=1);

namespace App\Entity\Traits\Timestampable;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtEntityTrait
{
    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Mapping\Annotation\Timestampable(on="update")
     */
    protected ?DateTimeInterface $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
