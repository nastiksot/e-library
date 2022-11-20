<?php

declare(strict_types=1);

namespace App\CQ\Event\Reading;

use App\CQ\Command\Reading\ResetPenaltyReadingCommand;
use App\CQ\Event\EventHandlerInterface;
use App\Service\MessageBusHandler;

class ReadingProlongAcceptedHandler implements EventHandlerInterface
{
    public function __construct(
        private MessageBusHandler $messageBusHandler,
    ) {
    }

    public function __invoke(ReadingProlongAcceptedEvent $event): void
    {
        $this->messageBusHandler->handleCommand(new ResetPenaltyReadingCommand($event->getReadingId()));
    }
}
