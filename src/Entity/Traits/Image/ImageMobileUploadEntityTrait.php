<?php

declare(strict_types=1);

namespace App\Entity\Traits\Image;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use function property_exists;

trait ImageMobileUploadEntityTrait
{
    /**
     * @ORM\Column(name="image_mobile", type="string", length=255, nullable=true)
     */
    protected ?string $imageMobile = null;

    /**
     * @Vich\UploadableField(mapping="default", fileNameProperty="imageMobile")
     */
    protected ?File $imageMobileFile = null;

    public function getImageMobile(): ?string
    {
        return $this->imageMobile;
    }

    public function setImageMobile(?string $image): self
    {
        $this->imageMobile = $image;

        return $this;
    }

    public function setImageMobileFile(File|UploadedFile|null $imageFile): self
    {
        $this->imageMobileFile = $imageFile;

        if ($imageFile && property_exists($this, 'updatedAt')) {
            try {
                $this->updatedAt = new DateTime();
            } catch (Exception $e) {
            }
        }

        return $this;
    }

    public function getImageMobileFile(): ?File
    {
        return $this->imageMobileFile;
    }
}
