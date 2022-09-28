<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\WishList\GetWishListByUidQuery;
use App\Entity\WishList\WishList;
use App\Service\MessageBusHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\UuidV4;

class WishListUidValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    final public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return WishList::class === $argument->getType() &&
            null !== $request->attributes->get('wishListUid') &&
            UuidV4::isValid($request->attributes->get('wishListUid'));
    }

    final public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this
            ->messageBusHandler
            ->handleQuery(
                new GetWishListByUidQuery(
                    UuidV4::fromString($request->attributes->get('wishListUid')),
                    NormalizerFlags::FORMAT_ENTITY
                )
            );
    }
}
