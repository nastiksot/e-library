<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\CQ\Query\Translation\GetTranslationCollectionQuery;
use App\DTO\Collection\TranslationCollectionDTO;
use App\DTO\ErrorDTO;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api')]
class TranslationController extends AbstractController
{
    #[Route(
        path: '/translations',
        name: 'api.translation_collection',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns translations.",
     *     @Model(type=TranslationCollectionDTO::class, groups={"collection_meta", "translation_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Translations")
     */
    public function getCollectionAction(Request $request, MessageBusHandler $messageBusHandler): JsonResponse
    {
        $groups = ['groups' => ['translation_collection', 'collection_meta']];

        return $this->json(
            data: $messageBusHandler->handleQuery(new GetTranslationCollectionQuery($request->getLocale())),
            context: $groups
        );
    }
}
