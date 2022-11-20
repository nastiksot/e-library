<?php

declare(strict_types=1);

namespace App\CQ\Event\Reading;

class ReadingPenaltyResetedEvent
{
    public function __construct(
        private int $readingId,
    ) {
    }

    public function getReadingId(): int
    {
        return $this->readingId;
    }
}
