<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\DTO\ErrorDTO;
use App\Exception\AccessDeniedException;
use App\Exception\AppException;
use App\Exception\EntityNotFoundException;
use App\Exception\SendMailException;
use App\Exception\UnprocessableEntityException;
use App\Exception\ValidationFailedException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\ValidationFailedException as SymfonyValidationFailedException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\Serializer\SerializerInterface;
use function array_key_exists;
use function get_class;
use function in_array;

class ApiExceptionToErrorConverter implements EventSubscriberInterface
{
    private const EXCEPTIONS_TO_CATCH = [
        InvalidOptionsException::class => Response::HTTP_BAD_REQUEST,
        EntityNotFoundException::class => Response::HTTP_NOT_FOUND,
        AccessDeniedException::class   => Response::HTTP_FORBIDDEN,
        AppException::class            => Response::HTTP_BAD_REQUEST,
        SendMailException::class       => Response::HTTP_UNPROCESSABLE_ENTITY,
    ];

    private const EXCEPTIONS_TO_NORMALIZE = [
        ValidationFailedException::class        => Response::HTTP_UNPROCESSABLE_ENTITY,
        UnprocessableEntityException::class     => Response::HTTP_UNPROCESSABLE_ENTITY,
        SymfonyValidationFailedException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
    ];

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!str_contains($event->getRequest()->getPathInfo(), '/api/')
            || !in_array('application/json', $event->getRequest()->getAcceptableContentTypes(), true)
        ) {
            return;
        }

        $throwable = $event->getThrowable();

        if ($throwable instanceof HandlerFailedException) {
            $throwable = $throwable->getPrevious();
        }

        $throwableClass = get_class($throwable);

        if (array_key_exists($throwableClass, self::EXCEPTIONS_TO_CATCH)) {
            $errorCode = self::EXCEPTIONS_TO_CATCH[$throwableClass];

            $event->setResponse(
                new JsonResponse(
                    $this->serializer->serialize(new ErrorDTO($errorCode, $throwable->getMessage()), 'json'),
                    $errorCode,
                    [],
                    true
                )
            );
        } elseif (array_key_exists($throwableClass, self::EXCEPTIONS_TO_NORMALIZE)) {
            $errorCode = self::EXCEPTIONS_TO_NORMALIZE[$throwableClass];

            $event->setResponse(
                new JsonResponse(
                    $this->serializer->serialize($throwable, 'json'),
                    $errorCode,
                    [],
                    true
                )
            );
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
