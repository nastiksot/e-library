<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use App\CQ\Command\CommandHandlerInterface;
use App\CQ\Query\Reading\GetExpiredForPenaltyReadingQuery;
use App\Entity\Reading;
use App\Repository\Settings\GeneralSettingsRepository;
use App\Service\MessageBusHandler;
use Doctrine\ORM\EntityManagerInterface;

class AssignPenaltyReadingHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusHandler $messageBusHandler,
        private GeneralSettingsRepository $generalSettingsRepository,
    ) {
    }

    public function __invoke(AssignPenaltyReadingCommand $command): void
    {
        // get settings
        $settings = $this->generalSettingsRepository->getSettings();

        /** @var Reading[] $readings - get expired reading for penalty */
        $readings = $this
            ->messageBusHandler
            ->handleQuery(new GetExpiredForPenaltyReadingQuery($command->getExpiredAt()));

        // assign penalty
        foreach ($readings as $reading) {
            $reading->setPenalty($settings->getPenalty());
        }

        // save
        $this->em->flush();
    }
}
