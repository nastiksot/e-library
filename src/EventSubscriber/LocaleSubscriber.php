<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\LocaleProvider\LocaleProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function array_key_exists;

class LocaleSubscriber //implements EventSubscriberInterface
{
//    public function __construct(
//        private LocaleProvider $localeProvider,
//        private string $adminLocale
//    ) {
//    }
//
//    public function onKernelRequest(RequestEvent $event): void
//    {
//        $request = $event->getRequest();
//
//        $allowedLocales = $this->localeProvider->getLocales();
//
//        if ($this->isAdminRoute($request)) {
//            $locale = $this->adminLocale;
//        } elseif ($this->isApiRoute($request)) {
//            $locale = $request->getPreferredLanguage($allowedLocales);
//        } else {
//            $locale = $request->attributes->get('_locale');
//
//            if (null === $locale || !array_key_exists($locale, $allowedLocales)) {
//                $locale = $this->localeProvider->getDefaultLocale();
//            }
//        }
//
//        $request->setLocale($locale);
//    }
//
//    public static function getSubscribedEvents(): array
//    {
//        return [
//            // must be registered before (i.e. with a higher priority than) the default Locale listener
//            KernelEvents::REQUEST => [['onKernelRequest', 20]],
//        ];
//    }
//
//    private function isAdminRoute(Request $request): bool
//    {
//        return $request->attributes->has('_sonata_admin')
//            || 'sonata_admin_dashboard' === $request->attributes->get('_route')
//            || str_contains($request->attributes->get('_route', ''), 'sonata_admin')
//            || str_starts_with($request->getPathInfo(), '/admin/')
//        ;
//    }
//
//    private function isApiRoute(Request $request): bool
//    {
//        return str_starts_with($request->getPathInfo(), '/api/');
//    }
}
