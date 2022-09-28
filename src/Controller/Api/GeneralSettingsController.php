<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\CQ\Query\GeneralSettings\GetGeneralSettingsQuery;
use App\DTO\ErrorDTO;
use App\Exception\ExceptionFactory;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/general-settings'
)]
class GeneralSettingsController extends AbstractController
{
    #[Route(
        path: '',
        name: 'api.general_settings',
        options: ['expose' => true],
        methods: ['GET'],
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns General settings",
     *     @Model(type=GeneralSettingsDTO::class, groups={"general_settings_details"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="General Settings")
     */
    public function load(
        MessageBusHandler $messageBusHandler,
        ExceptionFactory $exceptionFactory,
    ): JsonResponse {
        $groups          = ['groups' => ['general_settings_details']];
        $generalSettings = $messageBusHandler->handleQuery(new GetGeneralSettingsQuery());

        if (null === $generalSettings) {
            throw $exceptionFactory->createEntityNotFoundException();
        }

        return $this->json(
            data: $generalSettings,
            context: $groups
        );
    }
}
