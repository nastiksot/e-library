<?php

declare(strict_types=1);

namespace App\CQ\Command\Order;

use App\Contracts\Dictionary\OrderStatus;
use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Command\Reading\CreateReadingFromOrderCommand;
use App\Entity\Book\Book;
use App\Entity\Order;
use App\Entity\User\User;
use App\Exception\ExceptionFactory;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class DoneBookOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private string $adminTranslationDomain,
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    public function __invoke(DoneBookOrderCommand $command): Order
    {
        /** @var Order $order */
        $order = $this->em->getRepository(Order::class)->find($command->getOrderId());
        if (!$order) {
            throw $this->exceptionFactory->createEntityNotFoundException(
                'ORDER_ENTITY.MESSAGE.NOT_FOUND',
                [],
                $this->adminTranslationDomain
            );
        }

        // update
        $order->setStatus(OrderStatus::DONE());

        // save
        $this->em->flush();

        // create reading
        $this->messageBusHandler->handleCommand(new CreateReadingFromOrderCommand(orderId: $order->getId()));

        return $order;
    }
}
