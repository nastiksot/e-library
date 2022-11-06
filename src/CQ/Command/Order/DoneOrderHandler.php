<?php

declare(strict_types=1);

namespace App\CQ\Command\Order;

use App\Contracts\Dictionary\OrderStatus;
use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Event\Order\OrderDoneEvent;
use App\Entity\Order;
use App\Exception\ExceptionFactory;
use App\Repository\OrderRepository;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class DoneOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private MessageBusHandler $messageBusHandler,
        private OrderRepository $orderRepository,
    ) {
    }

    public function __invoke(DoneOrderCommand $command): Order
    {
        $order = $this->orderRepository->find($command->getOrderId());
        if (!$order) {
            throw $this->exceptionFactory->createEntityNotFoundException('ORDER_ENTITY.MESSAGE.NOT_FOUND');
        }

        // update
        $order->setStatus(OrderStatus::DONE());

        // save
        $this->em->flush();

        // event
        $this->messageBusHandler->handleEvent(new OrderDoneEvent($order->getId()));

        return $order;
    }
}
