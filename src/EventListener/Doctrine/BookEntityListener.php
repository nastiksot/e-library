<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\CQ\Query\Stock\GetStockQuery;
use App\Entity\Book\Book;
use App\Service\MessageBusHandler;

class BookEntityListener implements EntityListenerInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    public function postPersist(Book $book): void
    {
        $this->resolveStock($book);
    }

    public function postUpdate(Book $book): void
    {
        $this->resolveStock($book);
    }

    private function resolveStock(Book $book): void
    {
        // create stock
        $this->messageBusHandler->handleQuery(new GetStockQuery($book->getId()));
    }
}
