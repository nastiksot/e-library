<?php

declare(strict_types=1);

namespace App\CQ\Command\Stock;

/**
 * @see ReserveDoneStockHandler
 */
class ReserveDoneStockCommand
{
    public function __construct(
        private int $bookId,
        private int $quantity,
    ) {
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
