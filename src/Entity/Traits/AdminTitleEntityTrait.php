<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait AdminTitleEntityTrait
{
    /**
     * @ORM\Column(name="admin_title", type="string", length=255, nullable=true)
     */
    protected ?string $adminTitle = null;

    public function getAdminTitle(): ?string
    {
        return $this->adminTitle;
    }

    public function setAdminTitle(?string $adminTitle): self
    {
        $this->adminTitle = $adminTitle;

        return $this;
    }
}
