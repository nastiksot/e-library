<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Contracts\Serializer\Normalizer\NormalizerFlags;
use App\CQ\Query\ProductSet\GetProductSetCollectionQuery;
use App\DTO\Collection\ProductSetCollectionDTO;
use App\DTO\ErrorDTO;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function array_filter;
use function explode;

#[Route(path: '/api')]
class ProductSetController extends AbstractController
{
    #[Route(
        path: '/product-sets',
        name: 'api.product_set_collection',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Parameter(
     *     name="filters",
     *     in="query",
     *     description="Filter ids separated by hyphen-minus (-)",
     *     @OA\Schema(
     *         type="string",
     *     )
     * )
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
     *     description="Returns product sets.",
     *     @Model(type=ProductSetCollectionDTO::class, groups={"collection_meta", "product_set_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Product Sets")
     */
    public function getCollectionAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $groups = ['groups' => ['product_set_collection', 'collection_meta']];

        $dto = $normalizer->normalize(
            $messageBusHandler->handleQuery(
                new GetProductSetCollectionQuery(
                    $request->getLocale(),
                    (bool) $request->query->get('is_dealer_mode', false),
                    array_filter(explode('-', $request->query->get('filters', '')))
                )
            ),
            NormalizerFlags::FORMAT_DTO,
            $groups
        );

        return $this->json(
            data: $dto,
            context: $groups
        );
    }
}
