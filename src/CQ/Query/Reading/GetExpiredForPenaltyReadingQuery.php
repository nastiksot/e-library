<?php

declare(strict_types=1);

namespace App\CQ\Query\Reading;

use DateTimeInterface;

/**
 * @see GetExpiredForPenaltyReadingHandler
 */
class GetExpiredForPenaltyReadingQuery
{
    public function __construct(
        private DateTimeInterface $expiredAt,
    ) {
    }

    public function getExpiredAt(): DateTimeInterface
    {
        return $this->expiredAt;
    }
}
