<?php

declare(strict_types=1);

namespace App\Entity\Traits\Social;

use Doctrine\ORM\Mapping as ORM;

trait YoutubeSocialEntityTrait
{
    /**
     * @ORM\Column(name="social_youtube", type="string", length=255, nullable=true)
     */
    protected ?string $socialYoutube = null;

    public function getSocialYoutube(): ?string
    {
        return $this->socialYoutube;
    }

    public function setSocialYoutube(?string $socialYoutube): self
    {
        $this->socialYoutube = $socialYoutube;

        return $this;
    }
}
