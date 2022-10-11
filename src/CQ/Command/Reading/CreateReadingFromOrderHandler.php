<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use App\Contracts\Dictionary\OrderStatus;
use App\CQ\Command\CommandHandlerInterface;
use App\Entity\Book\Book;
use App\Entity\Order;
use App\Entity\Reading;
use App\Entity\User\User;
use App\Exception\ExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;

class CreateReadingFromOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private string $adminTranslationDomain,
    ) {
    }

    public function __invoke(CreateReadingFromOrderCommand $command): Reading
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

        return $reading;
    }
}
