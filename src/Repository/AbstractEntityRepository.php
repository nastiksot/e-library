<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contracts\Entity\EntityInterface;
use App\Contracts\Entity\ItemsListEntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;

/**
 * @method EntityInterface|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityInterface|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityInterface[]    findAll()
 * @method EntityInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class AbstractEntityRepository extends ServiceEntityRepository
{
    /**
     * @see EntityRepository::createQueryBuilder()
     */
    public function createCommonQueryBuilder(
        ?string $alias = null,
        ?string $indexBy = null,
        string|OrderBy|null $orderBy = null,
        ?string $orderDirection = null,
        bool $isActiveOnly = true,
    ): QueryBuilder {
        $alias = $alias ?? 'q';
        $qb    = $this->createQueryBuilder($alias, $indexBy);

        // only active
        if ($isActiveOnly && $this->getClassMetadata()->hasField('active')) {
            $qb
                ->andWhere($alias . '.active = :active')
                ->setParameter('active', true);
        }

        // order by
        if ($this->getClassMetadata()->hasField('position')) {
            $qb->orderBy($alias . '.position', 'ASC');
        }

        if (null !== $orderBy && null !== $orderDirection) {
            $qb->addOrderBy($orderBy, $orderDirection);
        }

        // return prepared query builder
        return $qb;
    }

    public function getOneById(int $id): ?EntityInterface
    {
        try {
            return $this->createCommonQueryBuilder('q')
                ->andWhere('q.id = :id')
                ->setParameter('id', $id)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @return EntityInterface[]
     */
    public function getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null): array
    {
        return $this->createCommonQueryBuilder('q', null, $orderBy, $orderDirection)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return EntityInterface[]
     */
    public function getAllByIds(array $ids, string|OrderBy|null $orderBy = null, ?string $orderDirection = null): array
    {
        if (!$ids) {
            return [];
        }

        $qb = $this->createCommonQueryBuilder('q', null, $orderBy, $orderDirection);
        $qb->andWhere($qb->expr()->in('q.id', $ids));

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @return EntityInterface[]
     */
    public function getLast(int $limit, string|OrderBy|null $orderBy = null, ?string $orderDirection = null): array
    {
        return $this->createCommonQueryBuilder('q', null, $orderBy, $orderDirection)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return EntityInterface[]
     */
    public function getRand(?int $limit = null): array
    {
        return $this->createCommonQueryBuilder()
            ->setMaxResults($limit)
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $sort  the ordering expression
     * @param string $order the ordering direction
     *
     * @return EntityInterface[]|null
     */
    public function getByItemsList(
        ItemsListEntityInterface $itemsListEntity,
        int $pageNum = 1,
        $sort = null,
        $order = null
    ) {
        switch ($itemsListEntity->getListType()) {
            case ItemsListEntityInterface::LIST_TYPE_LAST:
                return $this->getLast($itemsListEntity->getListLimit(), $sort, $order);

            case ItemsListEntityInterface::LIST_TYPE_RAND:
                return $this->getRand($itemsListEntity->getListLimit());

            case ItemsListEntityInterface::LIST_TYPE_PAGINATED:
                //return $this->getPager($pageNum, $itemsListEntity->getListLimit(), $sort, $order);

            case ItemsListEntityInterface::LIST_TYPE_ALL:
            default:
                return $this->getAll($sort, $order);
        }
    }

    public function getOneBySlug(string $slug, ?int $excludeId = null): ?EntityInterface
    {
        try {
            $qb = $this->createCommonQueryBuilder('t')
                ->andWhere('t.slug  = :slug')
                ->setParameter('slug', $slug);

            if (null !== $excludeId) {
                $qb
                    ->andWhere('t.id != :excludeId')
                    ->setParameter('excludeId', $excludeId);
            }

            return $qb
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
