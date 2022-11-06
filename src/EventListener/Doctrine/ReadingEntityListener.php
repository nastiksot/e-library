<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use App\Contracts\Dictionary\OrderStatus;
use App\CQ\Command\Stock\ReserveAddStockCommand;
use App\CQ\Command\Stock\ReserveCancelStockCommand;
use App\CQ\Command\Stock\ReserveDoneStockCommand;
use App\CQ\Command\Stock\ReserveRestoreStockCommand;
use App\Entity\Order;
use App\Entity\Reading;
use App\Service\MessageBusHandler;

class ReadingEntityListener implements EntityListenerInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    public function postPersist(Reading $reading): void
    {
//        $this->messageBusHandler->handleCommand(
//            new ReserveAddStockCommand(
//                $reading->getBook()->getId(),
//                $reading->getQuantity()
//            )
//        );
//
//        $this->messageBusHandler->handleCommand(
//            new ReserveDoneStockCommand(
//                $reading->getBook()->getId(),
//                $reading->getQuantity()
//            )
//        );
    }

    public function preRemove(Reading $reading): void
    {
//        $this->messageBusHandler->handleCommand(
//            new ReserveRestoreStockCommand(
//                $reading->getBook()->getId(),
//                $reading->getQuantity()
//            )
//        );
    }
}
