<?php

declare(strict_types=1);

namespace App\Admin\User\Profile;

use App\Admin\AbstractAdmin;
use App\Repository\User\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserProfileAdmin extends AbstractAdmin
{
    protected UserRepository $userRepository;

    #[Required]
    public function setUserRepository(
        UserRepository $userRepository,
    ): void {
        $this->userRepository = $userRepository;
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        /** @var ProxyQueryInterface|QueryBuilder $query */
        parent::configureQuery($query);

        $user = $this->getUser();
        if ($user !== null) {
            $alias = current($query->getRootAliases());
            $query->andWhere("{$alias}.user = :userId");
            $query->setParameter(':userId', $user->getId());
        }

        return $query;
    }
}
