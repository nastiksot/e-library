<?php

declare(strict_types=1);

namespace App\Entity\Traits\File;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use function property_exists;

trait FileUploadEntityTrait
{
    /**
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    protected ?string $fileName = null;

    /**
     * @Vich\UploadableField(mapping="default", fileNameProperty="fileName")
     */
    protected ?File $file = null;

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function setFile(File|UploadedFile|null $file): self
    {
        $this->file = $file;

        if ($file && property_exists($this, 'updatedAt')) {
            try {
                $this->updatedAt = new DateTime();
            } catch (Exception $e) {
            }
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }
}
