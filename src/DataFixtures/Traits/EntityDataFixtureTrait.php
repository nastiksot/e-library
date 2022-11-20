<?php

declare(strict_types=1);

namespace App\DataFixtures\Traits;

use App\Entity\AbstractEntity;

use function sprintf;

trait EntityDataFixtureTrait
{
    protected function createIndexKey(int $i): ?string
    {
        return $i <= 1 ? null : (string)sprintf("%04d", $i);
    }

    protected function createEntity(string $class, array $data): AbstractEntity
    {
        /** @var AbstractEntity $obj */
        $obj = (new $class);
        $obj->update($data);

        return $obj;
    }
}
