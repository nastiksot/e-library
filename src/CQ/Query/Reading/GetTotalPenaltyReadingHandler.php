<?php

declare(strict_types=1);

namespace App\CQ\Query\Reading;

use App\CQ\Query\QueryHandlerInterface;
use App\Repository\ReadingRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;

class GetTotalPenaltyReadingHandler implements QueryHandlerInterface
{
    public function __construct(
        private ReadingRepository $readingRepository,
    ) {
    }

    public function __invoke(GetTotalPenaltyReadingQuery $command): float
    {
        $qb = $this->readingRepository
            ->createQueryBuilder('q')
            ->select('SUM(q.penalty)');

        if ($command->getReadingIds()) {
            $qb->where($qb->expr()->in('q.id', $command->getReadingIds()));
        }

        if ($command->getUserIds()) {
            $qb->where($qb->expr()->in('q.user', $command->getUserIds()));
        }

        try {
            $total = $qb
                ->getQuery()
                ->getOneOrNullResult(Query::HYDRATE_SINGLE_SCALAR);
        } catch (NonUniqueResultException $e) {
            $total = null;
        }

        return (float)$total;
    }
}
