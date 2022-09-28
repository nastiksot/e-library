<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\CQ\Command\WishList\AddWishListProductSetCommand;
use App\CQ\Command\WishList\DeleteWishListProductSetCommand;
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
    path: '/api/users/{uid}/wish-lists/{wishListId}/product-sets',
    requirements: ['uid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}']
)]
class WishListProductSetController extends AbstractController
{
    #[Route(
        path: '',
        name: 'api.wish_list_product_set_post',
        options: ['expose' => true],
        methods: ['POST'],
    )]
    /**
     * @OA\RequestBody(
     *     request="body",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="product_set_id",
     *                  type="integer",
     *             ),
     *             @OA\Property(
     *                  property="products_quantity_ids",
     *                  default="[]"
     *             ),
     *             @OA\Property(
     *                  property="alternative_product_ids",
     *                  default="[]"
     *             ),
     *             @OA\Property(
     *                  property="delete_product_id",
     *                  type="integer",
     *             ),
     *             @OA\Property(
     *                  property="replace_product_id",
     *                  type="integer",
     *             ),
     *             @OA\Property(
     *                  property="replaced_product_ids",
     *                  default="[]"
     *             ),
     *             @OA\Property(
     *                  property="is_dealer_mode",
     *                  default="null"
     *             ),
     *         ),
     *     ),
     * )
     *
     * @OA\Response(
     *     response=204,
     *     description="Returns no content",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Wish List Product Sets")
     */
    public function postAction(
        Request $request,
        string $uid,
        int $wishListId,
        MessageBusHandler $messageBusHandler
    ): JsonResponse {
        $messageBusHandler->handleCommand(
            new AddWishListProductSetCommand(
                uid: UuidV4::fromString($uid),
                isDealerMode: (bool) $request->request->get('is_dealer_mode', false),
                wishListId: $wishListId,
                productSetId: $request->request->getInt('product_set_id'),
                productsQuantityIds: (array) $request->request->get('products_quantity_ids', []),
                alternativeProductIds: (array) $request->request->get('alternative_product_ids', []),
                deleteProductId: $request->request->getInt('delete_product_id'),
                replaceProductId: $request->request->getInt('replace_product_id'),
                replacedProductIds: (array) $request->request->get('replaced_product_ids', []),
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{wishListProductSetId}',
        name: 'api.wish_list_product_set_delete',
        options: ['expose' => true],
        methods: ['DELETE'],
    )]
    /**
     * @OA\Response(
     *     response=204,
     *     description="Returns no content",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Wish List Product Sets")
     */
    public function deleteAction(
        string $uid,
        int $wishListId,
        int $wishListProductSetId,
        MessageBusHandler $messageBusHandler
    ): JsonResponse {
        $messageBusHandler->handleCommand(
            new DeleteWishListProductSetCommand(
                uid: UuidV4::fromString($uid),
                wishListId: $wishListId,
                wishListProductSetId: $wishListProductSetId
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}
