<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Event\Reading\ReadingProlongCanceledEvent;
use App\Entity\Reading;
use App\Exception\ExceptionFactory;
use App\Repository\ReadingRepository;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class ProlongCancelReadingHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private MessageBusHandler $messageBusHandler,
        private ReadingRepository $readingRepository,
    ) {
    }

    public function __invoke(ProlongCancelReadingCommand $command): Reading
    {
        $reading = $this->readingRepository->find($command->getReadingId());
        if (!$reading) {
            throw $this->exceptionFactory->createEntityNotFoundException('READING_ENTITY.MESSAGE.NOT_FOUND');
        }

        // update
        $reading->setProlongAt(null);

        // save
        $this->em->flush();

        // event
        $this->messageBusHandler->handleEvent(new ReadingProlongCanceledEvent($reading->getId()));

        return $reading;
    }
}
