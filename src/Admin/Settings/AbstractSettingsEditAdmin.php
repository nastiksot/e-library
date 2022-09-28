<?php

declare(strict_types=1);

namespace App\Admin\Settings;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureFormTrait;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class AbstractSettingsEditAdmin extends AbstractAdmin
{
    use ConfigureFormTrait;

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->clearExcept(['edit']);
    }
}
