<?php

declare(strict_types=1);

namespace App\CQ\Event\Order;

/**
 * @see OrderCreatedHandler
 */
class OrderCreatedEvent
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
