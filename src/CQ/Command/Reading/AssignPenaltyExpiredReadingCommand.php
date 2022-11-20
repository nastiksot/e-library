<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use DateTimeInterface;

/**
 * @see AssignPenaltyExpiredReadingHandler
 */
class AssignPenaltyExpiredReadingCommand
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
