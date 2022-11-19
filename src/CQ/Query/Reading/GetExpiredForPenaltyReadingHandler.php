<?php

declare(strict_types=1);

namespace App\CQ\Query\Reading;

use App\CQ\Query\QueryHandlerInterface;
use App\Repository\ReadingRepository;

class GetExpiredForPenaltyReadingHandler implements QueryHandlerInterface
{
    public function __construct(
        private ReadingRepository $readingRepository,
    ) {
    }

    /**
     * @return array Reading[]
     */
    public function __invoke(GetExpiredForPenaltyReadingQuery $command): array
    {
        $qb = $this->readingRepository->createQueryBuilder('q');

        $qb->where('q.endAt <= :endAt')
            ->setParameter('endAt', $command->getExpiredAt())
            ->andWhere($qb->expr()->isNull('q.penalty'));

        return $qb->getQuery()->getResult();
    }
}
