<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\Repository\MessageRepository;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(
        path: '/login',
        name: 'web.login',
        options: ['expose' => true],
        methods: ['GET'],
    )]
    public function login(
        Request $request,
        MessageRepository $messageRepository,
        AuthenticationUtils $authUtils
    ): Response {
        $error        = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        $title        = $messageRepository->getMessageByCode($request->getLocale(), 'USER.LOGIN.TITLE');

        return $this->render(
            'default/security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
                'title'         => $title,
            ]
        );
    }

    #[Route(
        path: '/login-check',
        name: 'web.login_check',
        options: ['expose' => true],
        methods: ['POST']
    )]
    public function check(): void
    {
        throw new RuntimeException('Should be captured by security component');
    }
}
