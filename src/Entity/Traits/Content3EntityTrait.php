<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Content3EntityTrait
{
    /**
     * @ORM\Column(name="content3", type="text", nullable=true)
     */
    protected ?string $content3 = null;

    public function getContent3(): ?string
    {
        return $this->content3;
    }

    public function setContent3(?string $content3): self
    {
        $this->content3 = $content3;

        return $this;
    }
}
