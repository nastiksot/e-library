<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Event\Reading\ReadingPenaltyResetedEvent;
use App\Entity\Reading;
use App\Exception\ExceptionFactory;
use App\Repository\ReadingRepository;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class ResetPenaltyReadingHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private MessageBusHandler $messageBusHandler,
        private ReadingRepository $readingRepository,
    ) {
    }

    public function __invoke(ResetPenaltyReadingCommand $command): Reading
    {
        $reading = $this->readingRepository->find($command->getReadingId());
        if (!$reading) {
            throw $this->exceptionFactory->createEntityNotFoundException('READING_ENTITY.MESSAGE.NOT_FOUND');
        }

        // update
        $reading->setPenalty(null);

        // save
        $this->em->flush();

        // event
        $this->messageBusHandler->handleEvent(new ReadingPenaltyResetedEvent($reading->getId()));

        return $reading;
    }
}
