<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reading;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reading|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reading|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reading[]    findAll()
 * @method Reading[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Reading|null getOneById(int $id)
 * @method Reading[]    getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 * @method Reading[]    getAllByIds(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 */
class ReadingRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reading::class);
    }
}
