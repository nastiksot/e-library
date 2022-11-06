<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Stock|null getOneById(int $id)
 * @method Stock[]    getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 * @method Stock[]    getAllByIds(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 */
class StockRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }
}
