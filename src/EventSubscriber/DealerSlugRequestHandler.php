<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\Dealer\GetDealerBySlugQuery;
use App\Entity\User\Dealer;
use App\Service\MessageBusHandler;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class DealerSlugRequestHandler implements EventSubscriberInterface
{
    private ?bool $dealerWasNotFound = null;

    public function __construct(
        private MessageBusHandler $messageBusHandler
    ) {
    }

    #[ArrayShape([
        KernelEvents::REQUEST  => 'string',
        KernelEvents::RESPONSE => 'string',
    ])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST  => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    final public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (null !== $request->attributes->get('dealerSlug') &&
            null === $this->resolveDealer($request->attributes->get('dealerSlug'))
        ) {
            $this->dealerWasNotFound = true;
        }
    }

    final public function onKernelResponse(ResponseEvent $event): void
    {
        if (true === $this->dealerWasNotFound) {
            throw new NotFoundHttpException();
        }
    }

    private function resolveDealer(string $slug): ?Dealer
    {
        return $this
            ->messageBusHandler
            ->handleQuery(new GetDealerBySlugQuery(slug: $slug, format: NormalizerFlags::FORMAT_ENTITY));
    }
}
