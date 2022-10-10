<?php

declare(strict_types=1);

namespace App\CQ\Command\Order;

/**
 * @see CancelBookOrderHandler
 */
class CancelBookOrderCommand
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
