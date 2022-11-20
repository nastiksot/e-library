<?php

declare(strict_types=1);

namespace App\CQ\Query\Reading;

/**
 * @see GetTotalPenaltyReadingHandler
 */
class GetTotalPenaltyReadingQuery
{
    public function __construct(
        private array $readingIds = [],
        private array $userIds = [],
    ) {
    }

    public function getReadingIds(): array
    {
        return $this->readingIds;
    }

    public function getUserIds(): array
    {
        return $this->userIds;
    }
}
