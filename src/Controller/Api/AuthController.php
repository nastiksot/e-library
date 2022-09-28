<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\CQ\Command\User\ForgotPasswordUserCommand;
use App\CQ\Command\User\RegisterConfirmUserCommand;
use App\CQ\Command\User\RegisterUserCommand;
use App\CQ\Command\User\ResetPasswordUserCommand;
use App\CQ\Command\WishList\SaveWishListToUserCommand;
use App\DTO\ErrorDTO;
use App\Entity\User\User;
use App\Entity\WishList\WishList;
use App\EventSubscriber\UniqueIdentifierHandler;
use App\Exception\ExceptionFactory;
use App\Service\MessageBusHandler;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

#[Route(path: '/api/auth')]
class AuthController extends AbstractController
{
    #[Route(
        path: '/register',
        name: 'api.auth.register',
        options: ['expose' => true],
        methods: ['POST'],
    )]
    /**
     * @OA\RequestBody(
     *     request="body",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="accept_news",
     *                 type="boolean"
     *             ),
     *             @OA\Property(
     *                 property="accept_privacy_policy",
     *                 type="boolean"
     *             ),
     *             @OA\Property(
     *                 property="uid",
     *                 type="string",
     *             ),
     *             @OA\Property(
     *                  property="is_dealer_mode",
     *                  default="null"
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
    final public function register(
        Request $request,
        MessageBusHandler $messageBusHandler,
        ExceptionFactory $exceptionFactory,
    ): JsonResponse {
        $isRegisterToSaveConfiguration = $request->request->has('uid');

        // validate uid to save wishList to registered user
        if ($isRegisterToSaveConfiguration &&
            !UuidV4::isValid($request->request->get('uid', ''))
        ) {
            throw $exceptionFactory->createUnprocessableEntityException();
        }

        // register
        $uid = $isRegisterToSaveConfiguration
            ? UuidV4::fromString($request->attributes->get(UniqueIdentifierHandler::UID_NAME))
            : null;

        // register a user
        /** @var User $user */
        $user = $messageBusHandler->handleCommand(
            new RegisterUserCommand(
                locale: $request->getLocale(),
                uid: $uid,
                email: (string) $request->request->get('email', ''),
                acceptNews: $request->request->getBoolean('accept_news'),
                acceptProcessPersonalData: $request->request->getBoolean('accept_process_personal_data'),
                acceptPrivacyPolicy: $request->request->getBoolean('accept_privacy_policy'),
            )
        );

        // save current wishList to registered user
        if ($isRegisterToSaveConfiguration &&
            UuidV4::isValid($request->request->get('uid'))
        ) {
            /** @var WishList $wishList */
            $wishList = $messageBusHandler->handleCommand(
                new SaveWishListToUserCommand(
                    uid: UuidV4::fromString($request->request->get('uid')),
                    userIdentifier: $user->getUserIdentifier(),
                    isDealerMode: (bool) $request->request->get('is_dealer_mode', false),
                )
            );

            // update unique identifier
            $request->attributes->set(UniqueIdentifierHandler::UID_NAME, $wishList->getUid()->toRfc4122());
        }

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{token}/register-confirm',
        name: 'api.auth.register_confirm',
        requirements: ['token' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
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
     *                 property="password",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="confirm_password",
     *                 type="string"
     *             ),
     *         ),
     *     ),
     * )
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
    final public function registerConfirm(
        string $token,
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): JsonResponse {
        // register confirm
        $messageBusHandler->handleCommand(
            new RegisterConfirmUserCommand(
                token: UuidV4::fromString($token),
                password: (string) $request->request->get('password', ''),
                confirmPassword: (string) $request->request->get('confirm_password', ''),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/forgot-password',
        name: 'api.auth.forgot_password',
        options: ['expose' => true],
        methods: ['POST'],
    )]
    /**
     * @OA\RequestBody(
     *     request="body",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="email",
     *                 type="string"
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
    final public function forgotPassword(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): JsonResponse {
        $messageBusHandler->handleCommand(
            new ForgotPasswordUserCommand(
                email: (string) $request->request->get('email', ''),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }

    #[Route(
        path: '/{token}/reset-password',
        name: 'api.auth.reset_password',
        requirements: ['token' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
        options: ['expose' => true],
        methods: ['POST'],
    )]
    /**
     * @OA\RequestBody(
     *     request="body",
     *     @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             @OA\Property(
     *                 property="password",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="confirm_password",
     *                 type="string"
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
    final public function resetPassword(
        string $token,
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): JsonResponse {
        $messageBusHandler->handleCommand(
            new ResetPasswordUserCommand(
                token: UuidV4::fromString($token),
                password: (string) $request->request->get('password', ''),
                confirmPassword: (string) $request->request->get('confirm_password', ''),
            )
        );

        // success
        return $this->json(
            data: null,
            status: Response::HTTP_NO_CONTENT
        );
    }
}
