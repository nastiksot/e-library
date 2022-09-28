<?php

declare(strict_types=1);

namespace App\Entity\Traits\Timestampable;

trait TimestampableEntityTrait
{
    use CreatedAtEntityTrait;
    use UpdatedAtEntityTrait;
}
