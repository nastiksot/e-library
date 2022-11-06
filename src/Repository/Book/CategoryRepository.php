<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Entity\Book\Category;
use App\Repository\AbstractEntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Category|null getOneById(int $id)
 * @method Category[]    getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 * @method Category[]    getAllByIds(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 */
class CategoryRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
