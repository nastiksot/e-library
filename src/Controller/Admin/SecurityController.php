<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(
        path: '/library/login',
        name: 'admin.login',
        methods: ['GET'],
    )]
    public function login(
        AuthenticationUtils $authUtils
    ): Response {
        $error        = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            'admin/security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );
    }

    #[Route(
        path: '/library/logout',
        name: 'admin.logout',
        methods: ['GET'],
    )]
    public function logout(): void
    {
        throw new RuntimeException('Should be captured by security component');
    }

    #[Route(
        path: '/library/login-check',
        name: 'admin.login_check',
        methods: ['POST']
    )]
    public function check(): void
    {
        throw new RuntimeException('Should be captured by security component');
    }
}
