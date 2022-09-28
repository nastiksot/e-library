<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Entity\User\User;
use App\Exception\ExceptionFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use function str_contains;

#[Route(path: '/api')]
class SecurityController extends AbstractController
{
    use TargetPathTrait;

    #[Route(
        path: '/login-check',
        name: 'api.login_check',
        options: ['expose' => true],
        methods: ['POST']
    )]
    public function check(
        #[CurrentUser] ?User $user,
        Request $request,
        ExceptionFactory $exceptionFactory,
        RouterInterface $router,
    ): Response {
        if (null === $user) {
            throw $exceptionFactory->createValidationFailedException('USER.NOT_FOUND');
        }

        $targetUrl = $this->resolveTargetUrl($request->getSession(), $router);

        return $this->json(
            [
                'target_url' => $targetUrl,
            ]
        );
    }

    private function resolveTargetUrl(
        SessionInterface $session,
        RouterInterface $router,
        string $firewallName = 'main',
    ): string {
        $targetPath = $this->getTargetPath($session, $firewallName);

        if ($targetPath && str_contains($targetPath, '/api/')) {
            $targetPath = null;
        }

        return $targetPath ?? $router->generate('web.account');
    }
}
