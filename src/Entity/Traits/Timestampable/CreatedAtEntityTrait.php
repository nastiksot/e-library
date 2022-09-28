<?php

declare(strict_types=1);

namespace App\Entity\Traits\Timestampable;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

trait CreatedAtEntityTrait
{
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Mapping\Annotation\Timestampable(on="create")
     */
    protected ?DateTimeInterface $createdAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
