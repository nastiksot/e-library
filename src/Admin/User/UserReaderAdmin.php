<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class UserReaderAdmin extends AbstractUserAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->add('register');
        $collection->add('login');
    }

    protected function resolveUserRoles(): array
    {
        return [
            UserInterface::ROLE_READER,
        ];
    }

    protected function configureFormFields(FormMapper $form): void
    {
        parent::configureFormFields($form);
    }
}
