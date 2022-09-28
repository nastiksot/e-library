<?php

declare(strict_types=1);

namespace App\Entity\Traits\YoutubeVideo;

use Doctrine\ORM\Mapping as ORM;

trait YoutubeVideoUrlTrait
{
    /**
     * @ORM\Column(name="youtube_video_url", type="string", length=255, nullable=true)
     */
    private ?string $youtubeVideoUrl = null;

    public function getYoutubeVideoUrl(): ?string
    {
        return $this->youtubeVideoUrl;
    }

    public function setYoutubeVideoUrl(?string $youtubeVideoUrl): self
    {
        $this->youtubeVideoUrl = $youtubeVideoUrl;

        return $this;
    }
}
