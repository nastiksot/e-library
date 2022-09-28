<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\JsRoutingBundle\Extractor\ExposedRoutesExtractorInterface;
use FOS\JsRoutingBundle\Response\RoutesResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\AutoExpireFlashBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use function explode;

class RouteController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ExposedRoutesExtractorInterface $exposedRoutesExtractor,
        private bool $debug = false
    ) {
    }

    #[Route(path: 'data/routes.json')]
    public function index(Request $request): JsonResponse
    {
        $session = $request->hasSession() ? $request->getSession() : null;

        if (null !== $session && $request->hasPreviousSession() && $session->getFlashBag() instanceof AutoExpireFlashBag) {
            // keep current flashes for one more request if using AutoExpireFlashBag
            $session->getFlashBag()->setAll($session->getFlashBag()->peekAll());
        }

        $routesResponse = new RoutesResponse(
            $this->exposedRoutesExtractor->getBaseUrl(),
            $this->exposedRoutesExtractor->getRoutes(),
            $this->exposedRoutesExtractor->getPrefix($request->getLocale()),
            $this->exposedRoutesExtractor->getHost(),
            $this->exposedRoutesExtractor->getPort(),
            $this->exposedRoutesExtractor->getScheme(),
            $request->getLocale(),
            $request->query->has('domain') ? explode(',', $request->query->get('domain')) : []
        );

        $content = $this->serializer->serialize($routesResponse, 'json');

        return new JsonResponse(
            data: $content,
            json: true,
        );
    }
}
