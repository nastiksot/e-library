<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\DTO\Entity\UserDTO;
use App\Entity\User\User;
use Carbon\Carbon;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer
    ) {
    }

    /**
     * @param User $object
     */
    public function normalize($object, string $format = null, array $context = []): UserDTO|array
    {
        // normalize dates
        $createdAt             = $object->getCreatedAt() ? Carbon::instance($object->getCreatedAt()) : null;
        $updatedAt             = $object->getUpdatedAt() ? Carbon::instance($object->getUpdatedAt()) : null;
        $forgotPasswordToken   = $object->getForgotPasswordToken()?->__toString();
        $forgotPasswordValidAt = $object->getForgotPasswordValidAt()
            ? Carbon::instance($object->getForgotPasswordValidAt()) : null;
        $registerConfirmToken   = $object->getRegisterConfirmToken()?->__toString();
        $registerConfirmValidAt = $object->getRegisterConfirmValidAt()
            ? Carbon::instance($object->getRegisterConfirmValidAt()) : null;

        $dto = new UserDTO(
            id: $object->getId(),
            password: $object->getPassword(),
            email: $object->getEmail(),
            roles: $object->getRoles(),
            active: $object->isActive(),
            googleAuthenticatorEnabled: $object->isGoogleAuthenticatorEnabled(),
            googleAuthenticatorToken: $object->getGoogleAuthenticatorToken(),
            locale: $object->getLocale(),
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            firstName: $object->getFirstName(),
            lastName: $object->getLastName(),
            acceptNews: $object->isAcceptNews(),
            acceptProcessPersonalData: $object->isAcceptProcessPersonalData(),
            acceptPrivacyPolicy: $object->isAcceptPrivacyPolicy(),
            forgotPasswordToken: $forgotPasswordToken,
            forgotPasswordValidAt: $forgotPasswordValidAt,
            registerConfirmToken: $registerConfirmToken,
            registerConfirmValidAt: $registerConfirmValidAt,
        );

        if (NormalizerFlags::FORMAT_DTO === $format) {
            return $dto;
        }

        return $this->objectNormalizer->normalize($dto, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
