<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

/**
 * @see ProlongAcceptReadingHandler
 */
class ProlongAcceptReadingCommand
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
