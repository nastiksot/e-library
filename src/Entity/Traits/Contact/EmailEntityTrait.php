<?php

declare(strict_types=1);

namespace App\Entity\Traits\Contact;

use Doctrine\ORM\Mapping as ORM;

trait EmailEntityTrait
{
    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
