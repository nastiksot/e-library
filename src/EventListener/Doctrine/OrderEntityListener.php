<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Contracts\Dictionary\OrderStatus;
use App\CQ\Command\Stock\ReserveCancelStockCommand;
use App\CQ\Command\Stock\ReserveDeleteStockCommand;
use App\Entity\Order;
use App\Service\MessageBusHandler;

class OrderEntityListener implements EntityListenerInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    public function preRemove(Order $order): void
    {
        if (OrderStatus::OPEN()->getValue() === $order->getStatus()->getValue()) {
            $this->messageBusHandler->handleCommand(
                new ReserveCancelStockCommand(
                    $order->getBook()->getId(),
                    $order->getQuantity()
                )
            );
        }

        if (OrderStatus::DONE()->getValue() === $order->getStatus()->getValue()) {
            $this->messageBusHandler->handleCommand(
                new ReserveDeleteStockCommand(
                    $order->getBook()->getId(),
                    $order->getQuantity()
                )
            );
        }
    }
}
