<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Entity\Book\Book;
use App\Repository\AbstractEntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Book|null getOneById(int $id)
 * @method Book[]    getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 * @method Book[]    getAllByIds(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 */
class BookRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
}
