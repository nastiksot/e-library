<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use App\CQ\Command\CommandHandlerInterface;
use App\Entity\Reading;
use App\Exception\ExceptionFactory;
use Doctrine\ORM\EntityManagerInterface;

class ProlongCancelReadingHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ExceptionFactory $exceptionFactory,
        private string $adminTranslationDomain,
    ) {
    }

    public function __invoke(ProlongCancelReadingCommand $command): Reading
    {
        /** @var Reading $reading */
        $reading = $this->em->getRepository(Reading::class)->find($command->getReadingId());
        if (!$reading) {
            throw $this->exceptionFactory->createEntityNotFoundException(
                'READING_ENTITY.MESSAGE.NOT_FOUND',
                [],
                $this->adminTranslationDomain
            );
        }

        // update
        $reading->setProlongAt(null);

        // save
        $this->em->flush();

        return $reading;
    }
}
