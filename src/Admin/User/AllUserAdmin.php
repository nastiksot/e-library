<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Form\FormMapper;

class AllUserAdmin extends AbstractUserAdmin
{
    protected function resolveUserRoles(): array
    {
        return UserInterface::ALL_ROLES;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        parent::configureFormFields($form);
        $this->configureFormFieldsGoogleAuthenticator($form);
    }
}
