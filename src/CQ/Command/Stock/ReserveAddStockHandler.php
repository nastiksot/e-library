<?php

declare(strict_types=1);

namespace App\CQ\Command\Stock;

use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Query\Stock\GetStockQuery;
use App\Entity\Stock;
use App\Exception\ExceptionFactory;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class ReserveAddStockHandler implements CommandHandlerInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
    ) {
    }

    public function __invoke(ReserveAddStockCommand $command): Stock
    {
        /** @var Stock $stock */
        $stock = $this->messageBusHandler->handleQuery(new GetStockQuery($command->getBookId()));

        // validate
        if ($command->getQuantity() > $stock->getQuantity()) {
            throw $this->exceptionFactory->createEntityNotFoundException(
                'STOCK_ENTITY.MESSAGE.OUT_OF_STOCK.QUANTITY',
                ['%quantity%' => $command->getQuantity(), '%stock%' => $stock->getQuantity()]
            );
        }

        // resolve quantity
        $quantity = $stock->getQuantity();
        $quantity -= $command->getQuantity();

        // resolve reserved
        $reserved = $stock->getReserved();
        $reserved += $command->getQuantity();

        // update
        $stock->setQuantity($quantity);
        $stock->setReserved($reserved);

        // save
        $this->em->flush();

        return $stock;
    }
}
