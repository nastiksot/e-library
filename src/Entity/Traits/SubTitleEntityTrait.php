<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SubTitleEntityTrait
{
    /**
     * @ORM\Column(name="sub_title", type="string", length=255, nullable=true)
     */
    protected ?string $subTitle;

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(?string $subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }
}
