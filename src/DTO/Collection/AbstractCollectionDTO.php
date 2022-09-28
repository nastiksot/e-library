<?php

declare(strict_types=1);

namespace App\DTO\Collection;

use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractCollectionDTO
{
    protected array $items = [];

    /**
     * @Groups({"collection_meta"})
     */
    protected ?CollectionMetaDTO $meta = null;

    public function __construct(array $items, ?CollectionMetaDTO $meta = null)
    {
        $this->items = $items;
        $this->meta  = $meta;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getMeta(): ?CollectionMetaDTO
    {
        return $this->meta;
    }
}
