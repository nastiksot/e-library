<?php

declare(strict_types=1);

namespace App\Entity\Traits\SliderFile;

use App\Contracts\Dictionary\FileMimeType;
use App\Entity\Traits\FileMimeTypeEntityTrait;
use Doctrine\ORM\Mapping as ORM;

trait SliderFileMimeTypeEntityTrait
{
    use FileMimeTypeEntityTrait;

    /**
     * @ORM\Column(name="file_mobile_mime_type", type=FileMimeType::class, nullable=true)
     */
    private ?FileMimeType $fileMobileMimeType = null;

    public function getFileMobileMimeType(): ?FileMimeType
    {
        return $this->fileMobileMimeType;
    }

    public function setFileMobileMimeType(null|string|FileMimeType $fileMobileMimeType): self
    {
        if (is_string($fileMobileMimeType)) {
            $this->fileMobileMimeType = new FileMimeType($fileMobileMimeType);
        } else {
            $this->fileMobileMimeType = $fileMobileMimeType;
        }

        return $this;
    }
}
