<?php

declare(strict_types=1);

namespace App\Contracts\Entity;

interface ItemsListEntityInterface
{
    public const LIST_LIMIT = 10;

    public const LIST_TYPE_ALL       = 1;
    public const LIST_TYPE_LAST      = 2;
    public const LIST_TYPE_RAND      = 3;
    public const LIST_TYPE_PAGINATED = 4;

    public function getListType(): ?int;

    public function getListLimit(): ?int;
}
