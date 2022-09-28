<?php

declare(strict_types=1);

namespace App\DTO\Collection;

use Symfony\Component\Serializer\Annotation\Groups;

class CollectionMetaDTO
{
    /**
     * @Groups({"collection_meta"})
     */
    private CollectionPaginatorDTO $paginator;

    /**
     * @Groups({"collection_meta"})
     */
    private ?CollectionFiltersDTO $filters;

    public function __construct(CollectionPaginatorDTO $paginator, ?CollectionFiltersDTO $filters = null)
    {
        $this->paginator = $paginator;
        $this->filters   = $filters;
    }

    public function getPaginator(): CollectionPaginatorDTO
    {
        return $this->paginator;
    }

    public function getFilters(): ?CollectionFiltersDTO
    {
        return $this->filters;
    }
}
