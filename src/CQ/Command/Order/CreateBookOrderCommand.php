<?php

declare(strict_types=1);

namespace App\CQ\Command\Order;

use DateTimeInterface;

/**
 * @see CreateBookOrderHandler
 */
class CreateBookOrderCommand
{
    public function __construct(
        private int $bookId,
        private int $userId,
        private int $quantity,
        private string $readingType,
        private DateTimeInterface $startAt,
        private DateTimeInterface $endAt,
    ) {
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getReadingType(): string
    {
        return $this->readingType;
    }

    public function getStartAt(): DateTimeInterface
    {
        return $this->startAt;
    }

    public function getEndAt(): DateTimeInterface
    {
        return $this->endAt;
    }
}
