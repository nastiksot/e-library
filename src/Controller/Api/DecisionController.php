<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\CQ\Query\Decision\GetDecisionQuery;
use App\DTO\Entity\Decision\DecisionDTO;
use App\DTO\ErrorDTO;
use App\Exception\ExceptionFactory;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/api/decision'
)]
class DecisionController extends AbstractController
{
    #[Route(
        path: '/{id}/data',
        name: 'api.decision_data',
        options: ['expose' => true],
        defaults: ['id' => null],
        methods: ['GET'],
    )]
    /**
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
     *     description="Returns decision question data",
     *     @Model(type=DecisionDTO::class, groups={"decision_details"})
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Decision")
     */
    public function data(
        int $id,
        Request $request,
        MessageBusHandler $messageBusHandler,
        ExceptionFactory $exceptionFactory,
    ): JsonResponse {
        $groups   = ['groups' => ['decision_details']];
        $decision = $messageBusHandler->handleQuery(new GetDecisionQuery(
            id: $id,
            isDealerMode: (bool) $request->query->get('is_dealer_mode', false),
        ));

        if (null === $decision) {
            throw $exceptionFactory->createEntityNotFoundException();
        }

        return $this->json(
            data: $decision,
            context: $groups
        );
    }
}
