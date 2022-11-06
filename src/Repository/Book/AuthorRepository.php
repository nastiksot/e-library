<?php

declare(strict_types=1);

namespace App\Repository\Book;

use App\Entity\Book\Author;
use App\Repository\AbstractEntityRepository;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Author|null getOneById(int $id)
 * @method Author[]    getAll(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 * @method Author[]    getAllByIds(string|OrderBy|null $orderBy = null, ?string $orderDirection = null)
 */
class AuthorRepository extends AbstractEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }
}
