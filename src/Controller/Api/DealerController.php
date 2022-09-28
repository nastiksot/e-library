<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\CQ\Command\DealerRequest\CreateDealerRequestCommand;
use App\CQ\Query\Dealer\GetDealerByUidQuery;
use App\DTO\ErrorDTO;
use App\Entity\User\Dealer;
use App\Entity\WishList\WishList;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

#[Route(
    path: '/api/dealers',
)]
class DealerController extends AbstractController
{
    #[Route(
        path: '',
        name: 'api.dealers.collection',
        options: ['expose' => true],
        methods: ['GET']
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
     * @OA\Tag(name="Dealers")
     */
    final public function dealersCollection(): JsonResponse
    {
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{dealerUid}',
        name: 'api.dealers.details',
        requirements: ['dealerUid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns dealer data",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Dealers")
     */
    final public function dealersDetails(
        string $dealerUid,
        MessageBusHandler $messageBusHandler,
    ): JsonResponse {
        $groups    = ['groups' => ['dealer_details']];
        $dealerDTO = $messageBusHandler->handleQuery(new GetDealerByUidQuery(uid: UuidV4::fromString($dealerUid)));

        if (null === $dealerDTO) {
            throw $this->createNotFoundException();
        }

        return $this->json(
            data: $dealerDTO,
            context: $groups
        );
    }

    #[Route(
        path: '/{dealerUid}/wish-lists/{wishListUid}/requests',
        name: 'api.dealers.requests_post',
        requirements: [
            'dealerUid'   => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}',
            'wishListUid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}',
        ],
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
     *                  property="name",
     *                  type="string",
     *             ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *             ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *             ),
     *              @OA\Property(
     *                  property="address",
     *                  type="string",
     *             ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *             ),
     *             @OA\Property(
     *                  property="send_copy",
     *                  type="boolean",
     *                  default="false",
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
     * @OA\Tag(name="Dealers")
     */
    final public function requests(
        Request $request,
        string $dealerUid,
        string $wishListUid,
        MessageBusHandler $messageBusHandler,
        Dealer $dealer = null,
        WishList $wishList = null,
    ): JsonResponse {
        if (null === $dealer || null === $wishList) {
            throw $this->createNotFoundException();
        }

        $messageBusHandler->handleCommand(
            new CreateDealerRequestCommand(
                dealerUid: $dealer->getUid(),
                wishListUid: $wishList->getUid(),
                locale: $request->getLocale(),
                userId: $this->getUser()?->getId(),
                contactName: $request->request->get('contact_name'),
                email: $request->request->get('email'),
                phone: $request->request->get('phone'),
                address: $request->request->get('address'),
                message: $request->request->get('message'),
                sendCopy: $request->request->getBoolean('send_copy'),
            )
        );

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}
