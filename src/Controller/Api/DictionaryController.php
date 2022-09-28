<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\CQ\Query\Dictionary\GetLocalesListQuery;
use App\DTO\Collection\LocaleCollectionDTO;
use App\DTO\ErrorDTO;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/dictionary')]
class DictionaryController extends AbstractController
{
    #[Route(
        path: '/locales',
        name: 'api.dictionary.locales-list',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns locales list.",
     *     @Model(type=LocaleCollectionDTO::class, groups={"locale_collection"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Locales")
     */
    public function getLocalesList(Request $request, MessageBusHandler $messageBusHandler): JsonResponse
    {
        $groups      = ['groups' => ['locale_collection']];
        $localesList = $messageBusHandler->handleQuery(new GetLocalesListQuery($request->getLocale()));

        return $this->json(
            data: $localesList,
            context: $groups
        );
    }
}
