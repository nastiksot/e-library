<?php

declare(strict_types=1);

namespace App\DTO\Entity;

use Symfony\Component\Serializer\Annotation\Groups;

class GeneralSettingsDTO
{
    /**
     * @Groups({
     *      "general_settings_details",
     * })
     */
    private ?string $socialFacebook;

    /**
     * @Groups({
     *      "general_settings_details",
     * })
     */
    private ?string $socialYoutube;

    /**
     * @Groups({
     *      "general_settings_details",
     * })
     */
    private ?string $socialInstagram;

    public function __construct(
        ?string $socialFacebook = null,
        ?string $socialYoutube = null,
        ?string $socialInstagram = null,
    ) {
        $this->socialFacebook  = $socialFacebook;
        $this->socialYoutube   = $socialYoutube;
        $this->socialInstagram = $socialInstagram;
    }

    public function getSocialFacebook(): ?string
    {
        return $this->socialFacebook;
    }

    public function getSocialYoutube(): ?string
    {
        return $this->socialYoutube;
    }

    public function getSocialInstagram(): ?string
    {
        return $this->socialInstagram;
    }
}
