<?php

declare(strict_types=1);

namespace App\Entity\Traits\Social;

use Doctrine\ORM\Mapping as ORM;

trait FacebookSocialEntityTrait
{
    /**
     * @ORM\Column(name="social_facebook", type="string", length=255, nullable=true)
     */
    protected ?string $socialFacebook = null;

    public function getSocialFacebook(): ?string
    {
        return $this->socialFacebook;
    }

    public function setSocialFacebook(?string $socialFacebook): self
    {
        $this->socialFacebook = $socialFacebook;

        return $this;
    }
}
