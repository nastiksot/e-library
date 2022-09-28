<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

use App\Contracts\Dictionary\FileMimeType;
use Symfony\Component\HttpFoundation\File\File;

interface FileMimeTypeInterface
{
    public function getFileName(): ?string;

    public function getFile(): ?File;

    public function getFileMimeType(): ?FileMimeType;

    public function setFileMimeType(null|string|FileMimeType $fileMimeType): self;
}
