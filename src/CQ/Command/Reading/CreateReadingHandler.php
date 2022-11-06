<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Event\Reading\ReadingCreatedEvent;
use App\Entity\Reading;
use App\Exception\ExceptionFactory;
use App\Repository\OrderRepository;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class CreateReadingHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private MessageBusHandler $messageBusHandler,
        private OrderRepository $orderRepository,
    ) {
    }

    public function __invoke(CreateReadingCommand $command): Reading
    {
        $order = $this->orderRepository->find($command->getOrderId());
        if (!$order) {
            throw $this->exceptionFactory->createEntityNotFoundException('ORDER_ENTITY.MESSAGE.NOT_FOUND');
        }

        // create
        $reading = (new Reading())
            ->setBook($order->getBook())
            ->setUser($order->getUser())
            ->setOrder($order)
            ->setQuantity($order->getQuantity())
            ->setReadingType($order->getReadingType())
            ->setStartAt($order->getStartAt())
            ->setEndAt($order->getEndAt());

        // save
        $this->em->persist($reading);
        $this->em->flush();

        // event
        $this->messageBusHandler->handleEvent(new ReadingCreatedEvent($order->getId()));

        return $reading;
    }
}
