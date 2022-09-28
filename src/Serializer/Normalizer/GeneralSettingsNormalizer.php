<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\DTO\Entity\GeneralSettingsDTO;
use App\Entity\Settings\GeneralSettings;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class GeneralSettingsNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
    ) {
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof GeneralSettings;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * @param GeneralSettings $object
     */
    public function normalize($object, string $format = null, array $context = []): array|GeneralSettingsDTO
    {
        $dto = new GeneralSettingsDTO(
            socialFacebook: $object->getSocialFacebook(),
            socialYoutube: $object->getSocialYoutube(),
            socialInstagram: $object->getSocialInstagram(),
        );

        if (NormalizerFlags::FORMAT_DTO === $format) {
            return $dto;
        }

        return $this->objectNormalizer->normalize($dto, $format, $context);
    }
}
