<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\WishList\GetWishListCollectionQuery;
use App\CQ\Query\WishList\GetWishListQuery;
use App\DTO\Collection\WishListCollectionDTO;
use App\DTO\Entity\WishListDTO;
use App\DTO\ErrorDTO;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\UuidV4;

#[Route(
    path: '/api/users/{uid}/wish-lists',
    requirements: ['uid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}']
)]
class WishListController extends AbstractController
{
    #[Route(
        path: '',
        name: 'api.wish_list_collection',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns wish lists.",
     *     @Model(type=WishListCollectionDTO::class, groups={"collection_meta", "wish_list_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Wish Lists")
     */
    public function getCollectionAction(Request $request, string $uid, MessageBusHandler $messageBusHandler, NormalizerInterface $normalizer): JsonResponse
    {
        $groups = ['groups' => ['wish_list_collection', 'collection_meta']];

        $dto = $normalizer->normalize(
            $messageBusHandler->handleQuery(new GetWishListCollectionQuery(UuidV4::fromString($uid), $request->getLocale())),
            NormalizerFlags::FORMAT_DTO,
            $groups
        );

        return $this->json(
            data: $dto,
            context: $groups
        );
    }

    #[Route(
        path: '/latest',
        name: 'api.wish_list_latest_get',
        options: ['expose' => true],
        defaults: ['wishListId' => null],
        methods: ['GET'],
    )]
    #[Route(
        path: '/{wishListId}',
        name: 'api.wish_list_get',
        options: ['expose' => true],
        methods: ['GET'],
    )]
    /**
     * @OA\Parameter(
     *     name="is_dealer_mode",
     *     in="query",
     *     description="Flag: is it regular or dealer mode",
     *     @OA\Schema(
     *         type="boolean",
     *     )
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns specific lists",
     *     @Model(type=WishListDTO::class, groups={"wish_list_details"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Wish Lists")
     */
    public function getAction(Request $request, string $uid, ?int $wishListId, MessageBusHandler $messageBusHandler, NormalizerInterface $normalizer): JsonResponse
    {
        $groups = ['groups' => ['wish_list_details']];

        $dto = $normalizer->normalize(
            $messageBusHandler->handleQuery(new GetWishListQuery(
                uid: UuidV4::fromString($uid),
                id: $wishListId,
                locale: $request->getLocale(),
                isDealerMode: (bool) $request->query->get('is_dealer_mode', false),
            )),
            NormalizerFlags::FORMAT_DTO,
            $groups
        );

        return $this->json(
            data: $dto,
            context: $groups
        );
    }
}
