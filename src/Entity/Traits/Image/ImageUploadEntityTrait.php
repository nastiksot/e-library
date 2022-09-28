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

trait ImageUploadEntityTrait
{
    /**
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected ?string $image = null;

    /**
     * @Vich\UploadableField(mapping="default", fileNameProperty="image")
     */
    protected ?File $imageFile = null;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(File|UploadedFile|null $imageFile): self
    {
        $this->imageFile = $imageFile;

        if ($imageFile && property_exists($this, 'updatedAt')) {
            try {
                $this->updatedAt = new DateTime();
            } catch (Exception $e) {
            }
        }

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
}
