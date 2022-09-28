<?php

declare(strict_types=1);

namespace App\Entity\Traits\Image;

use Doctrine\ORM\Mapping as ORM;

trait ImageUrlEntityTrait
{
    /**
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    protected ?string $imageUrl = null;

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}
