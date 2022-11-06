<?php

declare(strict_types=1);

namespace App\CQ\Command\Stock;

use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Query\Stock\GetStockQuery;
use App\Entity\Stock;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class ReserveCancelStockHandler implements CommandHandlerInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
        private EntityManagerInterface $em,
    ) {
    }

    public function __invoke(ReserveCancelStockCommand $command): Stock
    {
        /** @var Stock $stock */
        $stock = $this->messageBusHandler->handleQuery(new GetStockQuery($command->getBookId()));

        // update quantity
        $quantity = $stock->getQuantity();
        $quantity += $command->getQuantity();
        $stock->setQuantity($quantity);

        // update reserved
        $reserved = $stock->getReserved();
        $reserved -= $command->getQuantity();
        $stock->setReserved($reserved);

        // save
        $this->em->flush();

        return $stock;
    }
}
