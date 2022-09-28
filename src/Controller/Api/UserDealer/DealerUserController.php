<?php

declare(strict_types=1);

namespace App\Controller\Api\UserDealer;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Command\DealerUser\CreateDealerUserCommand;
use App\CQ\Command\DealerUser\DeleteDealerUserCommand;
use App\CQ\Command\DealerUser\UpdateDealerUserCommand;
use App\CQ\Query\DealerUser\GetDealerUserCollectionQuery;
use App\CQ\Query\DealerUser\GetDealerUserQuery;
use App\DTO\Collection\DealerUserCollectionDTO;
use App\DTO\ErrorDTO;
use App\Entity\User\Dealer;
use App\Entity\User\User;
use App\Security\Voter\UserDealerVoter\DealerUserVoter;
use App\Serializer\Normalizer\DealerUserNormalizer;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route(
    path: '/api/dealers/{dealerUid}/users',
    requirements: ['dealerUid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
)]
class DealerUserController extends AbstractUserDealerController
{
    #[Route(
        path: '',
        name: 'api.dealerUser.collection',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Return Dealer User collection",
     *     @Model(type=DealerUserCollectionDTO::class, groups={"collection_meta", "dealer_user_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="User Dealers")
     */
    final public function getCollectionAction(
        MessageBusHandler $messageBusHandler,
        NormalizerInterface $normalizer,
        ?Dealer $dealer = null,
    ): JsonResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $this->checkPermissions(DealerUserVoter::LIST, User::class, $dealer->getId());

        $groups = ['groups' => ['dealer_user_collection', 'collection_meta']];

        $dto = $normalizer->normalize(
            $messageBusHandler->handleQuery(
                new GetDealerUserCollectionQuery($dealer->getId())
            ),
            NormalizerFlags::FORMAT_DTO,
            $groups
        );

        return $this->json(
            data: $dto,
            context: $groups
        );
    }

    private function isCurrentUser(int $id): bool
    {
        return $this->getUser()?->getId() === $id;
    }

    #[Route(
        path: '/{id}',
        name: 'api.dealerUser.account.delete',
        requirements: ['id' => '\d+'],
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
     * @OA\Tag(name="User Dealers")
     */
    final public function deleteAccountAction(
        int $id,
        MessageBusHandler $messageBusHandler,
        TokenStorageInterface $tokenStorage,
        ?Dealer $dealer = null,
    ): JsonResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $user = $this->getUserEntity($id, $messageBusHandler);

        $this->checkPermissions(DealerUserVoter::DELETE, $user, $dealer->getId());

        $isCurrentUser = $this->isCurrentUser($id);

        $messageBusHandler->handleCommand(
            new DeleteDealerUserCommand(
                dealerUserId: $id,
                dealerId: $dealer->getId(),
            )
        );

        if ($isCurrentUser) {
            // current user was deleted => logout
            $tokenStorage->setToken(null);
        }

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    private function getUserEntity(int $id, MessageBusHandler $messageBusHandler): User
    {
        $user = $messageBusHandler->handleQuery(
            new GetDealerUserQuery(
                id: $id,
                format: NormalizerFlags::FORMAT_ENTITY
            )
        );

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        return $user;
    }

    #[Route(
        path: '/{id}',
        name: 'api.dealerUser.account.details',
        requirements: ['id' => '\d+'],
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Get User data",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="User Dealers")
     */
    final public function getAccountAction(
        int $id,
        MessageBusHandler $messageBusHandler,
        DealerUserNormalizer $dealerUserNormalizer,
        ?Dealer $dealer = null,
    ): Response {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $user = $this->getUserEntity($id, $messageBusHandler);

        $this->checkPermissions(DealerUserVoter::VIEW, $user, $dealer->getId());

        $groups = ['groups' => ['dealer_user_details', 'collection_meta']];

        // success
        return $this->json(
            data: $dealerUserNormalizer->normalize($user),
            context: $groups
        );
    }

    #[Route(
        path: '/{id}',
        name: 'api.dealerUser.account.update',
        requirements: ['id' => '\d+'],
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
     *                 property="first_name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="role",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="active",
     *                 type="boolean"
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
     * @OA\Tag(name="User Dealers")
     */
    final public function updateAccountAction(
        int $id,
        Request $request,
        MessageBusHandler $messageBusHandler,
        TokenStorageInterface $tokenStorage,
        ?Dealer $dealer = null,
    ): Response {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $user = $this->getUserEntity($id, $messageBusHandler);

        $this->checkPermissions(DealerUserVoter::EDIT, $user, $dealer->getId());

        $isCurrentUser = $this->isCurrentUser($id);
        $isActive      = $request->request->getBoolean('active');
        $role          = $request->request->get('role');

        $messageBusHandler->handleCommand(
            new UpdateDealerUserCommand(
                id: $user->getId(),
                firstName: (string) $request->request->get('first_name', ''),
                lastName: (string) $request->request->get('last_name', ''),
                email: (string) $request->request->get('email', ''),
                role: $role,
                password: $request->request->get('password'),
                active: $isActive,
            )
        );

        if ($isCurrentUser && !$isActive) {
            // current user was deactivated => logout
            $tokenStorage->setToken(null);
        }

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '',
        name: 'api.dealerUser.account.create',
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
     *                 property="first_name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="role",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="active",
     *                 type="boolean"
     *             ),
     *         ),
     *     ),
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="Returns link to User list",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="User Dealers")
     */
    final public function createAccountAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
        ?Dealer $dealer = null,
    ): Response {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $this->checkPermissions(DealerUserVoter::ADD, User::class, $dealer->getId());

        $messageBusHandler->handleCommand(
            new CreateDealerUserCommand(
                firstName: (string) $request->request->get('first_name', ''),
                lastName: (string) $request->request->get('last_name', ''),
                email: (string) $request->request->get('email', ''),
                password: (string) $request->request->get('password', ''),
                role: (string) $request->request->get('role', ''),
                active: $request->request->getBoolean('active', false),
                dealer: $dealer,
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_CREATED
        );
    }
}
