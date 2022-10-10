<?php

declare(strict_types=1);

namespace App\CQ\Command\Order;

use App\CQ\Command\CommandHandlerInterface;
use App\Entity\Book\Book;
use App\Entity\Order;
use App\Entity\User\User;
use App\Exception\ExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;

class CreateBookOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private string $adminTranslationDomain,
    ) {
    }

    public function __invoke(CreateBookOrderCommand $command): Order
    {
        $book = $this->em->getRepository(Book::class)->find($command->getBookId());
        if (!$book) {
            throw $this->exceptionFactory->createEntityNotFoundException(
                'BOOK_ENTITY.MESSAGE.NOT_FOUND',
                [],
                $this->adminTranslationDomain
            );
        }

        $user = $this->em->getRepository(User::class)->find($command->getUserId());
        if (!$user) {
            throw $this->exceptionFactory->createEntityNotFoundException(
                'USER_ENTITY.MESSAGE.NOT_FOUND',
                [],
                $this->adminTranslationDomain
            );
        }

        // create
        $order = (new Order())
            ->setBook($book)
            ->setUser($user)
            ->setQuantity($command->getQuantity())
            ->setReadingType($command->getReadingType())
            ->setStartAt($command->getStartAt())
            ->setEndAt($command->getEndAt());

        // save
        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }
}
