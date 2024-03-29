<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Entity\User\User;
use App\Repository\AbstractEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method User|null getOneById(int $id)
 * @method User[]    getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 * @method User[]    getAllByIds(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 */
class UserRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getOneByIdentifier(string $identifier): ?User
    {
        try {
            return $this->createQueryBuilder('u')
                ->where('u.email = :email')
                ->setParameters(
                    [
                        'email' => $identifier,
                    ]
                )
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException) {
            return null;
        }
    }

}
