<?php

declare(strict_types=1);

namespace App\Entity\Traits\Contact;

use Doctrine\ORM\Mapping as ORM;

trait PhoneEntityTrait
{
    /**
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected ?string $phone = null;

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
