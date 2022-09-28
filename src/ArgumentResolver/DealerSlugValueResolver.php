<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\Dealer\GetDealerBySlugQuery;
use App\Entity\User\Dealer;
use App\Service\MessageBusHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class DealerSlugValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    final public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return Dealer::class === $argument->getType() &&
            null !== $request->attributes->get('dealerSlug');
    }

    final public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this
            ->messageBusHandler
            ->handleQuery(
                new GetDealerBySlugQuery(
                    $request->attributes->get('dealerSlug'),
                    NormalizerFlags::FORMAT_ENTITY
                )
            );
    }
}
