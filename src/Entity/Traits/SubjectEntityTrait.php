<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SubjectEntityTrait
{
    /**
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    protected ?string $subject = null;

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }
}
