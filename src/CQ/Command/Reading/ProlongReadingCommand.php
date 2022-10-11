<?php

declare(strict_types=1);

namespace App\CQ\Command\Reading;

use DateTimeInterface;

/**
 * @see ProlongReadingHandler
 */
class ProlongReadingCommand
{
    public function __construct(
        private int $readingId,
        private DateTimeInterface $prolongAt,
    ) {
    }

    public function getReadingId(): int
    {
        return $this->readingId;
    }

    public function getProlongAt(): DateTimeInterface
    {
        return $this->prolongAt;
    }
}
