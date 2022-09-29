<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends AbstractUserAdmin
{
    protected function resolveUserRoles(): array
    {
        return [
            UserInterface::ROLE_SUPER_ADMIN,
            UserInterface::ROLE_ADMIN,
            UserInterface::ROLE_EDITOR,
        ];
    }

    protected function configureFormFields(FormMapper $form): void
    {
        parent::configureFormFields($form);
        $this->configureFormFieldsGoogleAuthenticator($form);
    }
}
