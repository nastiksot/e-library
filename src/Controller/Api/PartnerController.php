<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\Controller\AbstractController;
use App\CQ\Query\Partner\GetPartnerCollectionQuery;
use App\DTO\Collection\PartnerCollectionDTO;
use App\DTO\ErrorDTO;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route(
    path: '/api/partner'
)]
class PartnerController extends AbstractController
{
    #[Route(
        path: '/list',
        name: 'api.partner_list',
        options: ['expose' => true],
        methods: ['GET'],
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns partners lists.",
     *     @Model(type=PartnerCollectionDTO::class, groups={"collection_meta", "partner_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Partners")
     */
    public function list(
        Request $request,
        MessageBusHandler $messageBusHandler,
        NormalizerInterface $normalizer,
    ): JsonResponse {
        $collection = $messageBusHandler->handleQuery(new GetPartnerCollectionQuery());
        $groups     = ['groups' => ['collection_meta', 'partner_collection']];
        $dto        = $normalizer->normalize($collection, NormalizerFlags::FORMAT_DTO, $groups);

        return $this->json(
            data: $dto,
            context: $groups
        );
    }
}
