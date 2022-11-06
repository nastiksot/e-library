<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;

use Throwable;

use function array_key_last;
use function explode;

class ExceptionFactory
{
    public const DOMAIN_VALIDATORS = 'validators';
    public const DOMAIN_ADMIN      = 'SonataAdminBundle';

    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @param array<int, array> $violations
     */
    final public function createValidationFailedException(
        string $message = 'GENERAL.VALIDATION_FAILED',
        array $parameters = [],
        array $violations = [],
        string $domain = self::DOMAIN_VALIDATORS,
        string $locale = null,
    ): ValidationFailedException {
        return ValidationFailedException::fromArray(
            $violations,
            $this->translator->trans($message, $parameters, $domain, $locale)
        );
    }

    final public function createPathValidationFailedException(
        string $path,
        ?string $message = null,
        array $parameters = [],
        ?string $value = null,
        ?string $generalMessage = null,
        string $domain = self::DOMAIN_VALIDATORS,
        string $locale = null,
    ): ValidationFailedException {
        return ValidationFailedException::fromArray(
            [
                [
                    'path'    => $path,
                    'message' => $message ? $this->translator->trans($message, $parameters, $domain, $locale) : null,
                    'value'   => $value,
                ],
            ],
            $this->translator->trans($generalMessage ?? 'GENERAL.VALIDATION_FAILED', [], 'validators')
        );
    }

    final public function createEntityNotFoundException(
        ?string $message = null,
        array $parameters = [],
        string $domain = self::DOMAIN_ADMIN,
        string $locale = null,
    ): EntityNotFoundException {
        return new EntityNotFoundException(
            $this->translator->trans($message ?? 'GENERAL.ENTITY_NOT_FOUND', $parameters, $domain, $locale)
        );
    }

    final public function createUnprocessableEntityException(
        ?string $message = null,
        array $parameters = [],
        string $domain = self::DOMAIN_ADMIN,
        string $locale = null,
    ): UnprocessableEntityException {
        return new UnprocessableEntityException(
            $this->translator->trans($message ?? 'GENERAL.ENTITY_UNPROCESSABLE', $parameters, $domain, $locale)
        );
    }

    final public function createSendMailException(
        ?string $message = null,
        array $parameters = [],
        string $domain = self::DOMAIN_VALIDATORS,
        string $locale = null,
    ): SendMailException {
        return new SendMailException(
            $this->translator->trans($message ?? 'GENERAL.SEND_MAIL_FAILED', $parameters, $domain, $locale)
        );
    }

    final public function createAccessDeniedException(
        ?string $message = null,
        array $parameters = [],
        string $domain = self::DOMAIN_VALIDATORS,
        string $locale = null,
    ): AccessDeniedException {
        return new AccessDeniedException(
            $this->translator->trans($message ?? 'GENERAL.ACCESS_DENIED', $parameters, $domain, $locale)
        );
    }

    final public function getLastPreviousMessage(Throwable $exception): string
    {
        if ($exception->getPrevious() !== null) {
            return $this->getLastPreviousMessage($exception->getPrevious());
        }

        return $exception->getMessage();
    }
}
