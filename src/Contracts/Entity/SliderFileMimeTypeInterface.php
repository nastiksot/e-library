<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use App\Contracts\Dictionary\FileMimeType;
use Symfony\Component\HttpFoundation\File\File;

interface SliderFileMimeTypeInterface extends FileMimeTypeInterface
{
    public function getFileMobileName(): ?string;

    public function getFileMobile(): ?File;

    public function getFileMobileMimeType(): ?FileMimeType;

    public function setFileMobileMimeType(null|string|FileMimeType $fileMobileMimeType): self;
}
