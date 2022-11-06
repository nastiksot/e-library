<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

/**
 * @see CreateReadingHandler
 */
class CreateReadingCommand
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
