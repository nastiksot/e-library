<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\CQ\Command\User\DeleteUserCommand;
use App\CQ\Command\User\UpdateUserCommand;
use App\CQ\Query\User\GetMeQuery;
use App\DTO\ErrorDTO;
use App\Entity\User\User;
use App\EventSubscriber\UniqueIdentifierHandler;
use App\Security\Voter\UserVoter;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Uid\UuidV4;

#[Route(path: '/api/users/me')]
class UserController extends AbstractController
{
    #[Route(
        path: '',
        name: 'api.me',
        options: ['expose' => true],
        methods: ['GET']
    )]
    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns current user info.",
     * )
     * @OA\Response(
     *     response="default",
     *     description="Error",
     *     @Model(type=ErrorDTO::class)
     * )
     * @OA\Tag(name="Users")
     */
    final public function me(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): JsonResponse {
        $groups     = ['groups' => ['me']];
        $uid        = $request->attributes->get(UniqueIdentifierHandler::UID_NAME);
        $identifier = $this->getUser()?->getUserIdentifier();
        $meDTO      = $messageBusHandler->handleQuery(
            new GetMeQuery(
                uid: UuidV4::fromString($uid),
                identifier: $identifier,
                locale: $request->getLocale()
            )
        );

        return $this->json(
            data: $meDTO,
            context: $groups
        );
    }

    #[Route(
        path: '/profile',
        name: 'api.me.profile',
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
     *                 property="first_name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="last_name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="accept_news",
     *                 type="boolean"
     *             ),
     *             @OA\Property(
     *                 property="accept_process_personal_data",
     *                 type="boolean"
     *             ),
     *             @OA\Property(
     *                 property="accept_privacy_policy",
     *                 type="boolean"
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
     * @OA\Tag(name="Users")
     */
    final public function profile(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): JsonResponse {
        $messageBusHandler->handleCommand(
            new UpdateUserCommand(
                id: $this->getUser()->getId(),
                firstName: (string) $request->request->get('first_name', ''),
                lastName: (string) $request->request->get('last_name', ''),
                email: (string) $request->request->get('email', ''),
                password: $request->request->get('password'),
                acceptNews: $request->request->getBoolean('accept_news'),
                acceptProcessPersonalData: $request->request->getBoolean('accept_process_personal_data'),
                acceptPrivacyPolicy: $request->request->getBoolean('accept_privacy_policy'),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/profile',
        name: 'api.me.profile.delete',
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
     * @OA\Tag(name="Users")
     */
    final public function deleteProfile(
        MessageBusHandler $messageBusHandler,
        TokenStorageInterface $tokenStorage,
    ): JsonResponse {
        $user = $this->getUser();

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $messageBusHandler->handleCommand(
            new DeleteUserCommand(
                userId: $user->getId(),
            )
        );

        // current user was deleted => logout
        $tokenStorage->setToken(null);

        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}
