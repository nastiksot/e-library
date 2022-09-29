<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Form\FormMapper;

class UserLibrarianAdmin extends AbstractUserAdmin
{
    protected function resolveUserRoles(): array
    {
        return [
            UserInterface::ROLE_LIBRARIAN,
        ];
    }

    protected function configureFormFields(FormMapper $form): void
    {
        parent::configureFormFields($form);
    }
}
