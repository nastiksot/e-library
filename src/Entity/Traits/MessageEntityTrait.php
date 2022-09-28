<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait MessageEntityTrait
{
    /**
     * @ORM\Column(name="message", type="text", length=65535, nullable=true)
     */
    private ?string $message;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
