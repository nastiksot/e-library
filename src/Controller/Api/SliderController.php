<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\CQ\Query\Slider\GetFirstSliderQuery;
use App\DTO\Entity\Slider\SliderDTO;
use App\DTO\ErrorDTO;
use App\Exception\ExceptionFactory;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/slider'
)]
class SliderController extends AbstractController
{
    #[Route(
        path: '/first',
        name: 'api.slider_first',
        options: ['expose' => true],
        defaults: ['id' => null],
        methods: ['GET'],
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns the first slider data",
     *     @Model(type=SliderDTO::class, groups={"slider_details"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Slider")
     */
    public function first(
        MessageBusHandler $messageBusHandler,
        ExceptionFactory $exceptionFactory,
    ): JsonResponse {
        $groups = ['groups' => ['slider_details']];
        $slider = $messageBusHandler->handleQuery(new GetFirstSliderQuery());

        if (null === $slider) {
            throw $exceptionFactory->createEntityNotFoundException();
        }

        return $this->json(
            data: $slider,
            context: $groups
        );
    }
}
