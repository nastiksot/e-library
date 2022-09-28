<?php

declare(strict_types=1);

namespace App\Entity\Traits\Contact;

use Doctrine\ORM\Mapping as ORM;

trait AddressEntityTrait
{
    /**
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    protected ?string $address = null;

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
