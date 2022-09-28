<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Subject2EntityTrait
{
    /**
     * @ORM\Column(name="subject2", type="string", length=255, nullable=true)
     */
    protected ?string $subject2 = null;

    public function getSubject2(): ?string
    {
        return $this->subject2;
    }

    public function setSubject2(?string $subject2): self
    {
        $this->subject2 = $subject2;

        return $this;
    }
}
