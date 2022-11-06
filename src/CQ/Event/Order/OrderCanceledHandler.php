<?php

declare(strict_types=1);

namespace App\CQ\Event\Order;

use App\CQ\Command\Stock\ReserveCancelStockCommand;
use App\CQ\Event\EventHandlerInterface;
use App\Exception\ExceptionFactory;
use App\Repository\OrderRepository;
use App\Service\MessageBusHandler;

class OrderCanceledHandler implements EventHandlerInterface
{
    public function __construct(
        private ExceptionFactory $exceptionFactory,
        private MessageBusHandler $messageBusHandler,
        private OrderRepository $orderRepository,
    ) {
    }

    public function __invoke(OrderCanceledEvent $event): void
    {
        $order = $this->orderRepository->find($event->getOrderId());
        if (!$order) {
            throw $this->exceptionFactory->createEntityNotFoundException('ORDER_ENTITY.MESSAGE.NOT_FOUND');
        }

        $this->messageBusHandler->handleCommand(
            new ReserveCancelStockCommand(
                $order->getBook()->getId(),
                $order->getQuantity()
            )
        );
    }
}
