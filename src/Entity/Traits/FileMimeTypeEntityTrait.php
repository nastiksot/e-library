<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\Contracts\Dictionary\FileMimeType;
use Doctrine\ORM\Mapping as ORM;

trait FileMimeTypeEntityTrait
{
    /**
     * @ORM\Column(name="file_mime_type", type=FileMimeType::class, nullable=true)
     */
    private ?FileMimeType $fileMimeType = null;

    public function getFileMimeType(): ?FileMimeType
    {
        return $this->fileMimeType;
    }

    public function setFileMimeType(null|string|FileMimeType $fileMimeType): self
    {
        if (is_string($fileMimeType)) {
            $this->fileMimeType = new FileMimeType($fileMimeType);
        } else {
            $this->fileMimeType = $fileMimeType;
        }

        return $this;
    }
}
