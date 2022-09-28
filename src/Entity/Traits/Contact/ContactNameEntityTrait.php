<?php

declare(strict_types=1);

namespace App\Entity\Traits\Contact;

use Doctrine\ORM\Mapping as ORM;

trait ContactNameEntityTrait
{
    /**
     * @ORM\Column(name="contact_name", type="string", length=255, nullable=true)
     */
    protected ?string $contactName = null;

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(?string $contactName): self
    {
        $this->contactName = $contactName;

        return $this;
    }
}
