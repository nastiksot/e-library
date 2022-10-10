<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

/**
 * @see CreateReadingFromOrderHandler
 */
class CreateReadingFromOrderCommand
{
    public function __construct(
        private int $orderId,
    ) {
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

}
