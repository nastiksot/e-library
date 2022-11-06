<?php

declare(strict_types=1);

namespace App\CQ\Query\Stock;

use App\CQ\Query\QueryHandlerInterface;
use App\Entity\Book\Book;
use App\Entity\Stock;
use App\Exception\ExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;

class GetStockHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
    ) {
    }

    public function __invoke(GetStockQuery $query): Stock
    {
        // get book
        $book = $this->em->getRepository(Book::class)->find($query->getBookId());
        if (!$book) {
            throw $this->exceptionFactory->createEntityNotFoundException('BOOK_ENTITY.MESSAGE.NOT_FOUND');
        }

        // resolve stock
        $stock = $this->em->getRepository(Stock::class)->findOneBy(['book' => $query->getBookId()]);
        if (!$stock) {
            $stock = (new Stock())->setBook($book);
            $this->em->getUnitOfWork()->persist($stock);
            $this->em->getUnitOfWork()->commit($stock);
//            $this->em->persist($stock);
//            $this->em->flush();
        }

        return $stock;
    }
}
