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

trait FileMobileUploadEntityTrait
{
    /**
     * @ORM\Column(name="file_mobile", type="string", length=255, nullable=true)
     */
    protected ?string $fileMobileName = null;

    /**
     * @Vich\UploadableField(mapping="default", fileNameProperty="fileMobileName")
     */
    protected ?File $fileMobile = null;

    public function getFileMobileName(): ?string
    {
        return $this->fileMobileName;
    }

    public function setFileMobileName(?string $fileMobileName): self
    {
        $this->fileMobileName = $fileMobileName;

        return $this;
    }

    public function setFileMobile(File|UploadedFile|null $fileMobile): self
    {
        $this->fileMobile = $fileMobile;

        if ($fileMobile && property_exists($this, 'updatedAt')) {
            try {
                $this->updatedAt = new DateTime();
            } catch (Exception $e) {
            }
        }

        return $this;
    }

    public function getFileMobile(): ?File
    {
        return $this->fileMobile;
    }
}
