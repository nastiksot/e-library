<?php

declare(strict_types=1);

namespace App\Controller\Api\UserDealer;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Command\UserDealerRequest\CreateDealerRequestCommentCommand;
use App\CQ\Command\UserDealerRequest\DeleteDealerRequestsCommand;
use App\CQ\Command\UserDealerRequest\DeleteDealerRequestCommentCommand;
use App\CQ\Command\UserDealerRequest\UpdateDealerRequestCommentCommand;
use App\CQ\Command\UserDealerRequest\UpdateDealerRequestsArchivedCommand;
use App\CQ\Command\UserDealerRequest\UpdateUserDealerRequestStatusCommand;
use App\CQ\Query\UserDealerRequest\GetUserDealerRequestCollectionQuery;
use App\CQ\Query\UserDealerRequest\GetUserDealerRequestCommentCollectionQuery;
use App\CQ\Query\UserDealerRequest\GetUserDealerRequestCommentQuery;
use App\CQ\Query\UserDealerRequest\GetUserDealerRequestQuery;
use App\DTO\Collection\CollectionMetaDTO;
use App\DTO\Collection\CollectionPaginatorDTO;
use App\DTO\Collection\DealerRequest\DealerRequestCollectionDTO;
use App\DTO\Collection\DealerRequest\DealerRequestCommentCollectionDTO;
use App\DTO\ErrorDTO;
use App\DTO\Paginator\PaginatorQueryDTO;
use App\Entity\DealerRequest\DealerRequest;
use App\Entity\DealerRequest\DealerRequestComment;
use App\Entity\User\Dealer;
use App\Security\Voter\UserDealerVoter\UserDealerRequestVoter;
use App\Serializer\Normalizer\DealerRequest\DealerRequestNormalizer;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function array_filter;
use function explode;

#[Route(
    path: '/api/dealers/{dealerUid}/requests',
    requirements: ['dealerUid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
)]
class UserDealerRequestController extends AbstractUserDealerController
{
    #[Route(
        path: '',
        name: 'api.dealerRequest.collection',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Parameter(
     *     name="p",
     *     in="query",
     *     description="Page serial number",
     *     @OA\Schema(
     *         type="int",
     *     )
     * )
     * @OA\Parameter(
     *     name="ipp",
     *     in="query",
     *     description="Request items per page",
     *     @OA\Schema(
     *         type="int",
     *     )
     * )
     * @OA\Parameter(
     *     name="statuses",
     *     in="query",
     *     description="Dealer request statuses separated by hyphen-minus (-)",
     *     @OA\Schema(
     *         type="string",
     *     )
     * )
     * @OA\Parameter(
     *     name="sb",
     *     in="query",
     *     description="Sort field name",
     *     @OA\Schema(
     *         type="string|null",
     *     )
     * )
     * @OA\Parameter(
     *     name="st",
     *     in="query",
     *     description="Sorting direction",
     *     @OA\Schema(
     *         type="string|null",
     *     )
     * )
     * @OA\Parameter(
     *     name="isArchivedRequestsPage",
     *     in="query",
     *     description="Flag: load archived or not archived requests ",
     *     @OA\Schema(
     *         type="int|null",
     *     )
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Return Dealer Request collection",
     *     @Model(type=DealerRequestCollectionDTO::class, groups={"collection_meta", "dealer_request_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Dealer Requests")
     */
    final public function getDealerRequestsCollectionAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
        NormalizerInterface $normalizer,
        DealerRequestNormalizer $dealerRequestNormalizer,
        ?Dealer $dealer = null,
    ): JsonResponse|RedirectResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_LIST, DealerRequest::class, $dealer->getId());

        $page   = (int) $request->get('p');
        $params = [
            'p'          => $page,
            'ipp'        => (int) $request->get('ipp', GetUserDealerRequestCollectionQuery::ITEMS_PER_PAGE),
            'statuses'   => $request->query->get('statuses', ''),
            'sb'         => $request->query->get('sb'),
            'st'         => $request->get('st'),
            'isArchived' => (bool) $request->get('isArchivedRequestsPage', false),
        ];

        /** @var PaginatorQueryDTO $paginatorQuery */
        $paginatorQuery = $messageBusHandler->handleQuery(
                new GetUserDealerRequestCollectionQuery(
                    $dealer->getId(),
                    $page,
                    $params['ipp'],
                    array_filter(explode('-', $params['statuses'])),
                    $params['isArchived'],
                    $params['sb'],
                    $params['st'],
                )
            );

        $paginatorMeta = $paginatorQuery->getMeta();

        // normalize dealer requests
        $dealerRequests = [];

        foreach ($paginatorQuery->getPaginator() as $dealerRequest) {
            $dealerRequests[] = $dealerRequestNormalizer->normalize($dealerRequest, NormalizerFlags::FORMAT_DTO);
        }

        $dealerRequestCollectionDTO = new DealerRequestCollectionDTO(
            $dealerRequests,
            new CollectionMetaDTO(new CollectionPaginatorDTO($page, $paginatorMeta->getItemsPerPage(), $paginatorMeta->getTotal()))
        );

        $groups = ['groups' => ['dealer_request_collection', 'collection_meta']];
        $dto    = $normalizer->normalize($dealerRequestCollectionDTO, NormalizerFlags::FORMAT_DTO, $groups);

        return $this->json(
            data: $dto,
            context: $groups
        );
    }

    private function parseStrDealerRequestIds(string $strDealerRequestIds): array
    {
        $arr = array_map('intval', explode('-', $strDealerRequestIds));

        return array_filter($arr);
    }

    #[Route(
        path: '/{strDealerRequestIds}',
        name: 'api.dealerRequest.item.delete',
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
     * @OA\Tag(name="Dealer Requests")
     */
    final public function deleteRequestsAction(
        MessageBusHandler $messageBusHandler,
        string $strDealerRequestIds,
        ?Dealer $dealer = null,
    ): JsonResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $dealerRequestIds = $this->parseStrDealerRequestIds($strDealerRequestIds);

        if (!$dealerRequestIds) {
            throw $this->createNotFoundException();
        }

        foreach ($dealerRequestIds as $dealerRequestId) {
            // check permissions for every Request
            $dealerRequest = $this->getDealerRequestEntity($dealerRequestId, $messageBusHandler);

            $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_DELETE, $dealerRequest, $dealer->getId());
        }

        $messageBusHandler->handleCommand(
            new DeleteDealerRequestsCommand(
                dealerRequestIds: $dealerRequestIds,
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{strDealerRequestIds}',
        name: 'api.dealerRequest.item.updateArchived',
        options: ['expose' => true],
        methods: ['PATCH'],
    )]
    /**
     * @OA\Parameter(
     *     name="isArchived",
     *     in="query",
     *     description="Value of `Archived` property: 1 - true, null - false",
     *     @OA\Schema(
     *         type="int|null",
     *     )
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
     * @OA\Tag(name="Dealer Requests")
     */
    final public function updateArchivedRequestsAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
        string $strDealerRequestIds,
        ?Dealer $dealer = null,
    ): JsonResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $dealerRequestIds = $this->parseStrDealerRequestIds($strDealerRequestIds);

        if (!$dealerRequestIds) {
            throw $this->createNotFoundException();
        }

        foreach ($dealerRequestIds as $dealerRequestId) {
            // check permissions for every Request
            $dealerRequest = $this->getDealerRequestEntity($dealerRequestId, $messageBusHandler);

            $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_ARCHIVED_UPDATE, $dealerRequest, $dealer->getId());
        }

        $messageBusHandler->handleCommand(
            new UpdateDealerRequestsArchivedCommand(
                dealerRequestIds: $dealerRequestIds,
                isArchived: (bool) $request->get('isArchived', false),
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{dealerRequestId}/update-status',
        name: 'api.dealerRequest.item.updateStatus',
        requirements: ['dealerRequestId' => '\d+'],
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
     *                 property="status",
     *                 type="string"
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
     * @OA\Tag(name="Dealer Requests")
     */
    final public function updateDealerRequestStatusAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
        int $dealerRequestId,
        ?Dealer $dealer = null,
    ): JsonResponse|RedirectResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $dealerRequest = $this->getDealerRequestEntity($dealerRequestId, $messageBusHandler);

        $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_STATUS_UPDATE, $dealerRequest, $dealer->getId());

        $messageBusHandler->handleCommand(
            new UpdateUserDealerRequestStatusCommand(
                id: $dealerRequestId,
                status: (string) $request->request->get('status'),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    private function getDealerRequestEntity(int $id, MessageBusHandler $messageBusHandler): DealerRequest
    {
        $dealerRequest = $messageBusHandler->handleQuery(
            new GetUserDealerRequestQuery(
                id: $id,
                format: NormalizerFlags::FORMAT_ENTITY
            )
        );

        if (null === $dealerRequest) {
            throw $this->createNotFoundException();
        }

        return $dealerRequest;
    }

    private function getDealerRequestCommentEntity(
        int $dealerRequestCommentId,
        int $dealerRequestId,
        MessageBusHandler $messageBusHandler): DealerRequestComment
    {
        $dealerRequestComment = $messageBusHandler->handleQuery(
            new GetUserDealerRequestCommentQuery(
                dealerRequestCommentId: $dealerRequestCommentId,
                dealerRequestId: $dealerRequestId,
                format: NormalizerFlags::FORMAT_ENTITY
            )
        );

        if (null === $dealerRequestComment) {
            throw $this->createNotFoundException();
        }

        return $dealerRequestComment;
    }

    #[Route(
        path: '/{dealerRequestId}/comments',
        name: 'api.dealerRequest.item.comment.collection',
        requirements: ['dealerRequestId' => '\d+'],
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Return Dealer Request Comments collection",
     *     @Model(type=DealerRequestCommentCollectionDTO::class, groups={"collection_meta", "dealer_request_comment_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Dealer Request Comments")
     */
    final public function getDealerRequestCommentsCollectionAction(
        MessageBusHandler $messageBusHandler,
        NormalizerInterface $normalizer,
        int $dealerRequestId,
        ?Dealer $dealer = null,
    ): JsonResponse|RedirectResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $dealerRequest = $this->getDealerRequestEntity($dealerRequestId, $messageBusHandler);

        $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_COMMENT_LIST, $dealerRequest, $dealer->getId());

        $groups = ['groups' => ['dealer_request_comment_collection', 'collection_meta']];

        $dto = $normalizer->normalize(
            $messageBusHandler->handleQuery(
                new GetUserDealerRequestCommentCollectionQuery($dealerRequestId)
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
        path: '/{dealerRequestId}/comments',
        name: 'api.dealerRequest.item.comment.add',
        requirements: ['dealerRequestId' => '\d+'],
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
     *                 property="commentText",
     *                 type="string"
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
     * @OA\Tag(name="Dealer Request Comments")
     */
    final public function addRequestCommentAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
        int $dealerRequestId,
        ?Dealer $dealer = null,
    ): JsonResponse|RedirectResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $dealerRequest = $this->getDealerRequestEntity($dealerRequestId, $messageBusHandler);

        $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_COMMENT_ADD, $dealerRequest, $dealer->getId());

        $messageBusHandler->handleCommand(
            new CreateDealerRequestCommentCommand(
                dealerRequestId: $dealerRequestId,
                commentText: (string) $request->request->get('commentText'),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{dealerRequestId}/comments/{dealerRequestCommentId}',
        name: 'api.dealerRequest.item.comment.update',
        requirements: ['dealerRequestId' => '\d+', 'dealerRequestCommentId' => '\d+'],
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
     *                 property="commentText",
     *                 type="string"
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
     * @OA\Tag(name="Dealer Request Comments")
     */
    final public function updateRequestCommentAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
        int $dealerRequestId,
        int $dealerRequestCommentId,
        ?Dealer $dealer = null,
    ): JsonResponse|RedirectResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $dealerRequestComment = $this->getDealerRequestCommentEntity($dealerRequestCommentId, $dealerRequestId, $messageBusHandler);

        $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_COMMENT_UPDATE, $dealerRequestComment, $dealer->getId());

        $messageBusHandler->handleCommand(
            new UpdateDealerRequestCommentCommand(
                dealerRequestCommentId: $dealerRequestCommentId,
                dealerRequestId: $dealerRequestId,
                commentText: (string) $request->request->get('commentText'),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{dealerRequestId}/comments/{dealerRequestCommentId}',
        name: 'api.dealerRequest.item.comment.delete',
        requirements: ['dealerRequestId' => '\d+', 'dealerRequestCommentId' => '\d+'],
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
     * @OA\Tag(name="Dealer Request Comments")
     */
    final public function deleteRequestCommentAction(
        MessageBusHandler $messageBusHandler,
        int $dealerRequestId,
        int $dealerRequestCommentId,
        ?Dealer $dealer = null,
    ): JsonResponse {
        if (null === $dealer) {
            throw $this->createNotFoundException();
        }

        $dealerRequestComment = $this->getDealerRequestCommentEntity($dealerRequestCommentId, $dealerRequestId, $messageBusHandler);

        $this->checkPermissions(UserDealerRequestVoter::DEALER_REQUEST_COMMENT_DELETE, $dealerRequestComment, $dealer->getId());

        $messageBusHandler->handleCommand(
            new DeleteDealerRequestCommentCommand(
                dealerRequestId: $dealerRequestId,
                dealerRequestCommentId: $dealerRequestCommentId,
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}
