<?php

declare(strict_types=1);

namespace App\Security;

use App\Admin\Book\BookAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Security\Handler\SecurityHandlerInterface;

class RoleSecurityHandler implements SecurityHandlerInterface
{

    public function __construct(
        private \Sonata\AdminBundle\Security\Handler\RoleSecurityHandler $roleSecurityHandler
    ) {
    }

    public function isGranted(AdminInterface $admin, $attributes, ?object $object = null): bool
    {
        // allow book list for all users
        if ($admin instanceof BookAdmin &&
            'LIST' === $attributes
        ) {
            return true;
        }

        return $this->roleSecurityHandler->isGranted($admin, $attributes, $object);
    }

    public function getBaseRole(AdminInterface $admin): string
    {
        return $this->roleSecurityHandler->getBaseRole($admin);
    }

    public function buildSecurityInformation(AdminInterface $admin): array
    {
        return $this->roleSecurityHandler->buildSecurityInformation($admin);
    }

    public function createObjectSecurity(AdminInterface $admin, object $object): void
    {
        $this->roleSecurityHandler->createObjectSecurity($admin, $object);
    }

    public function deleteObjectSecurity(AdminInterface $admin, object $object): void
    {
        $this->roleSecurityHandler->deleteObjectSecurity($admin, $object);
    }
}
