<?php

declare(strict_types=1);

namespace App\Admin;

use App\Contracts\Entity\EntityInterface;
use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin as BaseAbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Service\Attribute\Required;
use function is_object;
use function sprintf;
use function str_replace;

/**
 * @method EntityInterface getSubject()
 */
abstract class AbstractAdmin extends BaseAbstractAdmin
{
    protected const FORM_FIELD_CONTENT_CONFIG_BASE         = 'basic_config';
    protected const FORM_FIELD_CONTENT_CONFIG_ADVANCED     = 'advanced_config';
    protected const FORM_FIELD_CONTENT_CONFIG_SIMPLIFIED   = 'simplified_config';
    protected const FORM_FIELD_CONTENT_CONFIG_SIMPLIFIED_P = 'simplified_config_p';

    protected TokenStorageInterface $tokenStorage;
    protected ParameterBagInterface $parameterBag;
    protected RouterInterface       $routeGenerator;

    #[Required]
    public function init(
        TokenStorageInterface $tokenStorage,
        ParameterBagInterface $parameterBag,
        RouterInterface $routeGenerator
    ): void {
        $this->tokenStorage   = $tokenStorage;
        $this->parameterBag   = $parameterBag;
        $this->routeGenerator = $routeGenerator;

        $this->setTranslationDomain($this->parameterBag->get('adminTranslationDomain'));
    }

    protected function configure(): void
    {
        // resolve breadcrumb translation key
        $this->classnameLabel = sprintf(
            'BREADCRUMB.%s.%s',
            str_replace('.', '_', $this->getLabel()),
            $this->classnameLabel
        );
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('show');
        $collection->remove('export');
    }

    protected function configureBatchActions(array $actions): array
    {
        unset($actions['delete']);
        return parent::configureBatchActions($actions);
    }

    public function generateChildListUrl(AdminInterface $admin, EntityInterface $object): string
    {
        $routes = $admin->getRoutes();
        $route  = $routes->get('list');
        $id     = $admin->getNormalizedIdentifier($object);

        // return child url
        return $this->routeGenerator->generate($route->getDefault('_sonata_name'), ['id' => $id]);
    }

    public function generateChildEditUrl(
        AdminInterface $admin,
        EntityInterface $parent,
        EntityInterface $object
    ): string {
        $routes   = $admin->getRoutes();
        $route    = $routes->get('edit');
        $parentId = $admin->getNormalizedIdentifier($parent);
        $id       = $admin->getNormalizedIdentifier($object);

        // return edit child url
        return $this->routeGenerator
            ->generate(
                $route->getDefault('_sonata_name'),
                ['id' => $parentId, 'childId' => $id]
            );
    }

    public function setBaseRouteName(string $baseRouteName): void
    {
        $this->baseRouteName = $baseRouteName;
    }

    public function setBaseRoutePattern(string $baseRoutePattern): void
    {
        $this->baseRoutePattern = $baseRoutePattern;
    }

    protected function getUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if (null === $token || !is_object($user = $token->getUser()) || !$user instanceof UserInterface) {
            return null;
        }

        return $user;
    }

    protected function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        if (null === $domain) {
            $domain = $this->getTranslationDomain();
        }

        return $this->getTranslator()->trans($id, $parameters, $domain, $locale);
    }
}
