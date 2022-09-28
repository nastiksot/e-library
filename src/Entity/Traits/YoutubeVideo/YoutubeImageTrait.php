<?php

declare(strict_types=1);

namespace App\Entity\Traits\YoutubeVideo;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use function property_exists;

trait YoutubeImageTrait
{
    /**
     * @ORM\Column(name="youtube_image", type="string", length=255, nullable=true)
     */
    private ?string $youtubeImage = null;

    /**
     * @Vich\UploadableField(mapping="youtube", fileNameProperty="youtubeImage")
     */
    protected ?File $youtubeImageFile = null;

    public function getYoutubeImage(): ?string
    {
        return $this->youtubeImage;
    }

    public function setYoutubeImage(?string $youtubeImage): self
    {
        $this->youtubeImage = $youtubeImage;

        return $this;
    }

    public function setYoutubeImageFile(File|UploadedFile|null $youtubeImageFile): self
    {
        $this->youtubeImageFile = $youtubeImageFile;

        if ($youtubeImageFile && property_exists($this, 'updatedAt')) {
            try {
                $this->updatedAt = new DateTime();
            } catch (Throwable) {
            }
        }

        return $this;
    }

    public function getYoutubeImageFile(): ?File
    {
        return $this->youtubeImageFile;
    }
}
