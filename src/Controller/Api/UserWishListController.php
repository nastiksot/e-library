<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Contracts\Entity\UserInterface;
use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\Controller\AbstractController;
use App\CQ\Command\WishList\DeleteUserWishListCommand;
use App\CQ\Command\WishList\SaveWishListToUserCommand;
use App\CQ\Command\WishList\UpdateUserWishListCommand;
use App\CQ\Query\WishList\GetWishListUserCollectionQuery;
use App\DTO\Collection\WishListCollectionDTO;
use App\DTO\ErrorDTO;
use App\Entity\WishList\WishList;
use App\EventSubscriber\UniqueIdentifierHandler;
use App\Exception\ExceptionFactory;
use App\Security\Voter\UserWishListVoter;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\UuidV4;

#[Route(path: '/api/users/me/wish-lists')]
class UserWishListController extends AbstractController
{
    #[Route(
        path: '',
        name: 'api.user.wishlist.collection',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Return user's wish lists collection",
     *     @Model(type=WishListCollectionDTO::class, groups={"collection_meta", "user_wish_list_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="User WishList")
     */
    final public function wishListCollection(
        Request $request,
        MessageBusHandler $messageBusHandler,
        NormalizerInterface $normalizer,
        ExceptionFactory $exceptionFactory,
    ): JsonResponse {
        if (!$this->getUser() instanceof UserInterface) {
            throw $exceptionFactory->createUnprocessableEntityException();
        }

        $groups = ['groups' => ['user_wish_list_collection', 'collection_meta']];

        $dto = $normalizer->normalize(
            $messageBusHandler->handleQuery(
                new GetWishListUserCollectionQuery($this->getUser()->getId(), $request->getLocale())
            ),
            NormalizerFlags::FORMAT_DTO,
            $groups
        );

        return $this->json(
            data: $dto,
            context: $groups
        );
    }

    #[Route(
        path: '',
        name: 'api.user.wishlist.add',
        options: ['expose' => true],
        methods: ['POST'],
    )]
    /**
     * @OA\RequestBody(
     *     request="body",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="uid",
     *                 type="string",
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
     * @OA\Tag(name="Users")
     */
    final public function saveWishlist(
        Request $request,
        MessageBusHandler $messageBusHandler,
        ExceptionFactory $exceptionFactory,
    ): JsonResponse {
        if (null === $this->getUser()) {
            throw $exceptionFactory->createUnprocessableEntityException('USER.NOT_FOUND');
        }

        // validate uid to save wishList to registered user
        if ($request->request->has('uid') &&
            !UuidV4::isValid($request->request->get('uid', ''))
        ) {
            throw $exceptionFactory->createUnprocessableEntityException();
        }

        /** @var WishList $wishList */
        $wishList = $messageBusHandler->handleCommand(
            new SaveWishListToUserCommand(
                uid: UuidV4::fromString($request->request->get('uid')),
                userIdentifier: $this->getUser()->getUserIdentifier(),
                isDealerMode: (bool) $request->request->get('is_dealer_mode', false),
            )
        );

        // update unique identifier
        $request->attributes->set(UniqueIdentifierHandler::UID_NAME, $wishList->getUid()->toRfc4122());

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{wishListId}',
        name: 'api.user.wishlist.delete',
        options: ['expose' => true],
        methods: ['DELETE'],
    )]
    /**
     * @OA\Response(
     *     response=204,
     *     description="Delete user's wish list",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="User WishList")
     */
    final public function delete(
        int $wishListId,
        MessageBusHandler $messageBusHandler,
        ExceptionFactory $exceptionFactory,
    ): JsonResponse {
        if (!$this->getUser() instanceof UserInterface) {
            throw $exceptionFactory->createUnprocessableEntityException();
        }

        $messageBusHandler->handleCommand(
            new DeleteUserWishListCommand(
                userId: $this->getUser()->getId(),
                wishListId: $wishListId,
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{wishListUid}/update',
        name: 'api.user.wishlist.update',
        requirements: [
            'wishListUid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}',
            ],
        options: ['expose' => true],
        methods: ['PATCH'],
    )]
    /**
     * @OA\RequestBody(
     *     request="body",
     *     @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="wishListName",
     *                  type="string",
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
     * @OA\Tag(name="User WishList")
     */
    final public function requests(
        Request $request,
        MessageBusHandler $messageBusHandler,
        ExceptionFactory $exceptionFactory,
        WishList $wishList = null,
    ): JsonResponse {
        if (null === $wishList) {
            throw $this->createNotFoundException();
        }

        if (!$this->getUser() instanceof UserInterface) {
            throw $exceptionFactory->createUnprocessableEntityException();
        }

        $this->denyAccessUnlessGranted(UserWishListVoter::EDIT, $wishList);

        $messageBusHandler->handleCommand(
            new UpdateUserWishListCommand(
                wishListId: $wishList->getId(),
                userId: $this->getUser()->getId(),
                wishListName: $request->request->get('wishListName'),
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}
