<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Contracts\Entity\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Twig\Environment as Twig;
use function sprintf;
use function str_starts_with;

class AdminTwoStepAuthListener implements EventSubscriberInterface
{
    public function __construct(
        private GoogleAuthenticator $authenticator,
        private TokenStorageInterface $tokenStorage,
        private Twig $twig,
        private EntityManagerInterface $em
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!str_starts_with($event->getRequest()->getPathInfo(), '/admin')) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if (!$token instanceof UsernamePasswordToken) {
            return;
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // GoogleAuthenticator is disabled
        if (!$user->isGoogleAuthenticatorEnabled()) {
            return;
        }

        // GoogleAuthenticator is enabled
        // process it
        $request    = $event->getRequest();
        $sessionKey = $this->generateSessionKey($token);

        // google auth token is not set yet
        if (null === $user->getGoogleAuthenticatorToken()) {
            $event->setResponse($this->handleAuthResponse($request, $user, $sessionKey));

            return;
        }

        if (!$request->getSession()->has($sessionKey) ||
            true !== $request->getSession()->get($sessionKey)
        ) {
            $event->setResponse($this->handleConfirmResponse($request, $user, $sessionKey));
        }
    }

    protected function handleConfirmResponse(
        Request $request,
        UserInterface $user,
        string $sessionKey,
    ): Response {
        $session = $request->getSession();
        $error   = null;

        if ($request->isMethod('POST')) {
            if (true === $this->checkCode($user, $request->get('_code'))) {
                $session->set($sessionKey, true);

                return new RedirectResponse($request->getUri());
            }

            $error = 'SECURITY.TWO_STEP.CODE_INVALID';
        }

        return new Response(
            $this->twig->render(
                'admin/security/google-authentication.html.twig',
                [
                    'validate' => true,
                    'error'    => $error,
                ]
            )
        );
    }

    private function handleAuthResponse(
        Request $request,
        UserInterface $user,
        string $sessionKey,
    ): Response {
        $session   = $request->getSession();
        $secretKey = $sessionKey . '_secret';

        if (!$session->has($secretKey)) {
            $session->set($secretKey, $this->generateSecret());
        }

        $error = null;
        $user->setGoogleAuthenticatorToken($session->get($secretKey));

        if ($request->isMethod('POST')) {
            if (true === $this->checkCode($user, $request->get('_code'))) {
                $this->em->getUnitOfWork()->persist($user);
                $this->em->getUnitOfWork()->commit($user);
                $session->remove($sessionKey);

                return new RedirectResponse($request->getUri());
            }

            $error = 'SECURITY.TWO_STEP.CODE_INVALID';
        }

        return new Response(
            $this->twig->render(
                'admin/security/google-authentication.html.twig',
                [
                    'validate'  => false,
                    'error'     => $error,
                    'qrCodeImg' => $this->generateGoogleQrUrl($user),
                ]
            )
        );
    }

    private function checkCode(UserInterface $user, string $code): bool
    {
        return $this->authenticator->checkCode($user->getGoogleAuthenticatorToken(), $code);
    }

    private function generateGoogleQrUrl(UserInterface $user): string
    {
        return GoogleQrUrl::generate($user->getUserIdentifier(), $user->getGoogleAuthenticatorToken(), 'CEM');
    }

    private function generateSecret(): string
    {
        return $this->authenticator->generateSecret();
    }

    private function generateSessionKey(UsernamePasswordToken $token): string
    {
        return sprintf('_user_google_authenticator_%s_%s', $token->getFirewallName(), $token->getUserIdentifier());
    }
}
