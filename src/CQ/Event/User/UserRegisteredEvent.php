<?php

declare(strict_types=1);

namespace App\CQ\Event\User;

class UserRegisteredEvent
{
    public function __construct(
        private int $userId,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
