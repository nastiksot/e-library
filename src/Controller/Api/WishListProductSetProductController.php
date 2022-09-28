<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\CQ\Command\WishList\UpdateWishListProductSetProductQuantityCommand;
use App\DTO\ErrorDTO;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

#[Route(
    path: '/api/users/{uid}/wish-lists/{wishListId}/product-sets/{wishListProductSetId}/product/{wishListProductSetProductId}',
    requirements: ['uid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}']
)]
class WishListProductSetProductController extends AbstractController
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    #[Route(
        path: '/quantity',
        name: 'api.wish_list_product_set_product_quantity',
        options: ['expose' => true],
        methods: ['PATCH'],
    )]
    /**
     * @OA\RequestBody(
     *     request="body",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="quantity",
     *                 type="integer"
     *             ),
     *         ),
     *     ),
     * )
     * @OA\Response(
     *     response=204,
     *     description="Returns no content",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Response(
     *     response=400,
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Wish List Product Set Product")
     */
    public function quantityAction(
        string $uid,
        int $wishListProductSetProductId,
        Request $request,
    ): JsonResponse {
        // update
        $this->messageBusHandler->handleCommand(
            new UpdateWishListProductSetProductQuantityCommand(
                uid: UuidV4::fromString($uid),
                wishListProductSetProductId: $wishListProductSetProductId,
                quantity: $request->request->getInt('quantity'),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}
