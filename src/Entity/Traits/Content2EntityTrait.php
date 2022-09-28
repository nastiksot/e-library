<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Content2EntityTrait
{
    /**
     * @ORM\Column(name="content2", type="text", nullable=true)
     */
    protected ?string $content2 = null;

    public function getContent2(): ?string
    {
        return $this->content2;
    }

    public function setContent2(?string $content2): self
    {
        $this->content2 = $content2;

        return $this;
    }
}
