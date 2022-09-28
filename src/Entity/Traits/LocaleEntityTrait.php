<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait LocaleEntityTrait
{
    /**
     * @ORM\Column(name="locale", type="string", length=5, nullable=true)
     */
    protected ?string $locale = null;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}
