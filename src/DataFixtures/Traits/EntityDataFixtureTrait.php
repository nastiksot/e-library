<?php

namespace App\DataFixtures\Traits;

use App\Entity\AbstractEntity;
use function sprintf;

trait EntityDataFixtureTrait
{
    private function createIndexKey(int $i): ?string
    {
        return $i <= 1 ? null : (string)sprintf("%04d", $i);
    }

    private function createEntity(string $class, array $data): AbstractEntity
    {
        /** @var AbstractEntity $obj */
        $obj = (new $class);
        $obj->update($data);

        return $obj;
    }
}
