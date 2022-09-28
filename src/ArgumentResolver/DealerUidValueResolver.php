<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\Dealer\GetDealerByUidQuery;
use App\Entity\User\Dealer;
use App\Service\MessageBusHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\UuidV4;

class DealerUidValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    final public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return Dealer::class === $argument->getType() &&
            null !== $request->attributes->get('dealerUid') &&
            UuidV4::isValid($request->attributes->get('dealerUid'));
    }

    final public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this
            ->messageBusHandler
            ->handleQuery(
                new GetDealerByUidQuery(
                    UuidV4::fromString($request->attributes->get('dealerUid')),
                    NormalizerFlags::FORMAT_ENTITY
                )
            );
    }
}
