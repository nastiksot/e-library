<?php

declare(strict_types=1);

namespace App\CQ\Command\Order;

use App\Contracts\Dictionary\OrderStatus;
use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Event\Order\OrderCreatedEvent;
use App\Entity\Order;
use App\Exception\ExceptionFactory;
use App\Repository\Book\BookRepository;
use App\Repository\User\UserRepository;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class CreateOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private MessageBusHandler $messageBusHandler,
        private BookRepository $bookRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(CreateOrderCommand $command): Order
    {
        $book = $this->bookRepository->find($command->getBookId());
        if (!$book) {
            throw $this->exceptionFactory->createEntityNotFoundException('BOOK_ENTITY.MESSAGE.NOT_FOUND');
        }

        $user = $this->userRepository->find($command->getUserId());
        if (!$user) {
            throw $this->exceptionFactory->createEntityNotFoundException('USER_ENTITY.MESSAGE.NOT_FOUND');
        }

        // create
        $order = (new Order())
            ->setStatus(OrderStatus::OPEN())
            ->setBook($book)
            ->setUser($user)
            ->setQuantity($command->getQuantity())
            ->setReadingType($command->getReadingType())
            ->setStartAt($command->getStartAt())
            ->setEndAt($command->getEndAt());

        // save
        $this->em->persist($order);
        $this->em->flush();

        // event
        $this->messageBusHandler->handleEvent(new OrderCreatedEvent($order->getId()));

        return $order;
    }
}
