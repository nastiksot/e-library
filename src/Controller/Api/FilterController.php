<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\Filter\GetFilterCollectionQuery;
use App\DTO\Collection\FilterCollectionDTO;
use App\DTO\ErrorDTO;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route(path: '/api')]
class FilterController extends AbstractController
{
    #[Route(
        path: '/filters',
        name: 'api.filter_collection',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns filter groups with filters.",
     *     @Model(type=FilterCollectionDTO::class, groups={"collection_meta", "filter_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Filters")
     */
    public function getCollectionAction(Request $request, MessageBusHandler $messageBusHandler, NormalizerInterface $normalizer): JsonResponse
    {
        $groups = ['groups' => ['filter_collection', 'collection_meta']];

        $dto = $normalizer->normalize(
            $messageBusHandler->handleQuery(new GetFilterCollectionQuery($request->getLocale())),
            NormalizerFlags::FORMAT_DTO,
            $groups
        );

        return $this->json(
            data: $dto,
            context: $groups
        );
    }
}
