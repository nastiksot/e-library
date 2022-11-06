<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\CQ\Query\User\GetUserByForgotPasswordTokenQuery;
use App\CQ\Query\User\GetUserByRegisterConfirmTokenQuery;
use App\Repository\MessageRepository;
use App\Service\MessageBusHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

#[Route(path: '/auth')]
class AuthController extends AbstractController
{
    #[Route(
        path: '/register',
        name: 'web.auth.register',
        options: ['expose' => true]
    )]
    final public function register(Request $request, MessageRepository $messageRepository): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'USER.REGISTER.TITLE');

        return $this->render('default/auth/register.html.twig', ['title' => $title]);
    }

    #[Route(
        path: '/{token}/register-confirm',
        name: 'web.auth.register_confirm',
        requirements: ['token' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
        options: ['expose' => true],
        defaults: ['token' => null],
    )]
    final public function registerConfirm(
        Request $request,
        MessageRepository $messageRepository,
        MessageBusHandler $messageBusHandler,
        string $token,
    ): Response {
        $user = $messageBusHandler->handleQuery(new GetUserByRegisterConfirmTokenQuery(UuidV4::fromString($token)));

        if (null === $user) {
            throw $this->createNotFoundException('Token not found');
        }

        $title = $messageRepository->getMessageByCode($request->getLocale(), 'USER.REGISTER.TITLE');

        return $this->render('default/auth/register-confirm.html.twig', [
            'token' => $token,
            'user'  => $user,
            'title' => $title,
            ]
        );
    }

    #[Route(
        path: '/{token}/reset-password',
        name: 'web.auth.reset_password',
        requirements: ['token' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
        options: ['expose' => true],
        defaults: ['token' => null],
    )]
    final public function resetPassword(
        Request $request,
        MessageRepository $messageRepository,
        MessageBusHandler $messageBusHandler,
        string $token,
    ): Response {
        $user = $messageBusHandler->handleQuery(new GetUserByForgotPasswordTokenQuery(UuidV4::fromString($token)));

        if (null === $user) {
            throw $this->createNotFoundException('Token not found');
        }

        $title = $messageRepository->getMessageByCode($request->getLocale(), 'USER.RESET_PASSWORD.TITLE');

        return $this->render('default/auth/reset-password.html.twig', ['token' => $token, 'title' => $title]);
    }

    #[Route(
        path: '/forgot-password',
        name: 'web.auth.forgot_password',
        options: ['expose' => true],
    )]
    final public function forgotPassword(Request $request, MessageRepository $messageRepository): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'USER.FORGOT_PASSWORD.TITLE');

        return $this->render('default/auth/forgot-password.html.twig', ['title' => $title]);
    }
}
