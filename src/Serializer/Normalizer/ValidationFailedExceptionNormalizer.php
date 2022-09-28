<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\DTO\ErrorDTO;
use App\Exception\ValidationFailedException as AppValidationFailedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ValidationFailedException as SymfonyValidationFailedException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Contracts\Translation\TranslatorInterface;
use function count;
use function Symfony\Component\String\s;

class ValidationFailedExceptionNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private ObjectNormalizer $objectNormalizer,
        private TranslatorInterface $translator
    ) {
    }

    /**
     * @param AppValidationFailedException|SymfonyValidationFailedException $object
     */
    public function normalize($object, string $format = null, array $context = []): ErrorDTO|array
    {
        $errors = [];

        foreach ($object->getViolations() as $item) {
            $errors[s($item->getPropertyPath())->snake()->toString()] = $item->getMessage();
        }

        $message = $object instanceof SymfonyValidationFailedException ? $this->translator->trans('GENERAL.VALIDATION_FAILED', [], 'validators') : $object->getMessage();

        $dto = new ErrorDTO(Response::HTTP_UNPROCESSABLE_ENTITY, $message, [
            'fields' => count($errors) ? $errors : null,
        ]);

        if (NormalizerFlags::FORMAT_DTO === $format) {
            return $dto;
        }

        return $this->objectNormalizer->normalize($dto, $format, $context);
    }

    /**
     * @param mixed $data
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof AppValidationFailedException || $data instanceof SymfonyValidationFailedException;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
