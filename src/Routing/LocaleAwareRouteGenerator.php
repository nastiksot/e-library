<?php

declare(strict_types=1);

namespace App\Routing;

use Psr\Log\LoggerInterface;
use Symfony\Cmf\Component\Routing\ContentAwareGenerator;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LocaleAwareRouteGenerator //extends ContentAwareGenerator
{
//    public function __construct(
//        RouteProviderInterface $provider,
//        LoggerInterface $logger = null,
//        string $defaultLocale,
//    ) {
//        parent::__construct($provider, $logger);
//
//        $this->setDefaultLocale($defaultLocale);
//    }
//
//    public function generate($name, $parameters = [], $absolute = UrlGeneratorInterface::ABSOLUTE_PATH): string
//    {
//        try {
//            $locale = $parameters['_locale'] ?? $this->defaultLocale;
//
//            return parent::generate($name . '.' . $locale, $parameters, $absolute);
//        } catch (RouteNotFoundException) {
//            return parent::generate($name, $parameters, $absolute);
//        }
//    }
}
