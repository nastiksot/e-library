<?php

declare(strict_types=1);

namespace App\Routing;

use App\Service\LocaleProvider\LocaleProvider;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Loader\Configurator\ImportConfigurator;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use function implode;
use function is_dir;
use function opendir;
use function readdir;
use function realpath;
use function sha1;
use function str_ends_with;
use function str_replace;
use function strlen;
use function substr;
use const DIRECTORY_SEPARATOR;

class LocaleAwareRouteProvider //implements RouteProviderInterface
{

//    public function __construct(
//        private AnnotatedRouteControllerLoader $annotatedLoader,
//        private array $paths,
//        private string $projectDir,
//        private CacheInterface $localizedRoutesCache,
//        private LocaleProvider $localeProvider,
//        private bool $debug,
//    ) {
//    }
//
//    public function getRouteCollectionForRequest(Request $request): RouteCollection
//    {
//        return $this->getRouteCollection();
//    }
//
//    /**
//     * @param string $name
//     */
//    public function getRouteByName($name): Route
//    {
//        $route = $this->getRouteCollection()->get($name);
//
//        if (!$route) {
//            throw new RouteNotFoundException();
//        }
//
//        return $route;
//    }
//
//    /**
//     * @param array|null $names
//     */
//    public function getRoutesByNames($names): array
//    {
//        $result = [];
//
//        if (null !== $names) {
//            foreach ($names as $name) {
//                try {
//                    $result[] = $this->getRouteByName($name);
//                } catch (RouteNotFoundException) {
//                }
//            }
//        } else {
//            $result = $this->getRouteCollection()->all();
//        }
//
//        return $result;
//    }
//
//    private function getRouteCollection(): RouteCollection
//    {
//        $locales    = $this->localeProvider->getLocales();
//        $localeData = $this->localeProvider->getSummaryLocalesData();
//
//        if ($this->debug) {
//            return $this->doGetRouteCollection($localeData);
//        }
//
//        $key = sha1('route-locales-' . implode('__', $locales));
//
//        return $this->localizedRoutesCache->get($key, function (ItemInterface $item) use ($localeData) {
//            $item->expiresAfter(new DateInterval('P1D'));
//
//            return $this->doGetRouteCollection($localeData);
//        });
//    }
//
//    private function doGetRouteCollection(array $localeData): RouteCollection
//    {
//        $result = new RouteCollection();
//
//        $srcPath = realpath($this->projectDir . '/src');
//
//        foreach ($this->paths as $path) {
//            $realPath = realpath($this->projectDir . '/config/' . $path);
//
//            if ($realPath && is_dir($realPath)) {
//                $dirHandler = opendir($realPath);
//
//                while (false !== ($entry = readdir($dirHandler))) {
//                    if (str_ends_with($entry, '.php')) {
//                        $imported = $this->annotatedLoader->load(
//                            'App\\' . str_replace(DIRECTORY_SEPARATOR, '\\', substr($realPath, strlen($srcPath) + 1)) . '\\' . substr($entry, 0, -4),
//                            'annotation'
//                        );
//                        $result->addCollection($imported);
//                    }
//                }
//            }
//        }
//
//        $prefix = [];
//
//        foreach ($localeData as $locale) {
//            $prefix[$locale['locale']] = $locale['prefix'];
//        }
//
//        $configurator = new ImportConfigurator(new RouteCollection(), $result);
//        $configurator->prefix($prefix, false);
//
//        return $result;
//    }
}
