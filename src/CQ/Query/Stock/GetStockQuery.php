<?php

declare(strict_types=1);

namespace App\CQ\Query\Stock;

/**
 * @see GetStockHandler
 */
class GetStockQuery
{
    public function __construct(
        private int $bookId,
    ) {
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }
}
