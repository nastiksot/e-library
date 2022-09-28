<?php

declare(strict_types=1);

namespace App\DTO\Collection;

use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class CollectionPaginatorDTO
{
    /**
     * @Groups({"collection_meta"})
     * @OA\Property(property="current_page")
     */
    protected int $currentPage;

    /**
     * @Groups({"collection_meta"})
     * @OA\Property(property="items_per_page")
     */
    protected int $itemsPerPage;

    /**
     * @Groups({"collection_meta"})
     * @OA\Property(property="total_items")
     */
    protected int $totalItems;

    public function __construct(int $currentPage, int $itemsPerPage, int $totalItems)
    {
        $this->currentPage  = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->totalItems   = $totalItems;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }
}
