<?php

declare(strict_types=1);

namespace App\Entity\Traits\Image;

use Doctrine\ORM\Mapping as ORM;

trait ImageTitleEntityTrait
{
    /**
     * @ORM\Column(name="image_title", type="string", length=255, nullable=true)
     */
    protected ?string $imageTitle = null;

    public function getImageTitle(): ?string
    {
        return $this->imageTitle;
    }

    public function setImageTitle(?string $imageTitle): self
    {
        $this->imageTitle = $imageTitle;

        return $this;
    }
}
