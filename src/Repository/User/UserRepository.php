<?php

declare(strict_types=1);

namespace App\Repository\User;

use App\Contracts\Entity\ItemsListEntityInterface;
use App\Entity\User\User;
use App\Repository\AbstractEntityRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method User|null getOneById(int $id)
 * @method User[]    getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 * @method User[]    getLast(int $limit, $sort = null, ?string $order = null)
 * @method User[]    getRand(?int $limit = null)
 * @method User[]    getByItemsList(ItemsListEntityInterface $itemsListEntity, int $pageNum = 1, $sort = null, $order = null)
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
