<?php

declare(strict_types=1);

namespace App\DTO\Collection;

use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

class CollectionFiltersDTO
{
    /**
     * @Groups({"collection_meta"})
     */
    private ?string $query;

    /**
     * @Groups({"collection_meta"})
     * @OA\Property(
     *     property="filters",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         properties={
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             )
     *         }
     *     )
     * )
     */
    private ?array $filters;

    /**
     * @Groups({"collection_meta"})
     * @OA\Property(
     *     property="available_filters",
     *     type="array",
     *     @OA\Items(type="string")
     * )
     */
    private ?array $availableFilters;

    /**
     * @Groups({"collection_meta"})
     */
    private ?string $order;

    /**
     * @Groups({"collection_meta"})
     * @OA\Property(
     *     property="available_orders",
     *     type="array",
     *     @OA\Items(type="string")
     * )
     */
    private ?array $availableOrders;

    public function __construct(?string $query, ?array $filters, ?array $availableFilters, ?string $order, ?array $availableOrders)
    {
        $this->query            = $query;
        $this->filters          = $filters;
        $this->availableFilters = $availableFilters;
        $this->order            = $order;
        $this->availableOrders  = $availableOrders;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getFilters(): ?array
    {
        return $this->filters;
    }

    public function getAvailableFilters(): ?array
    {
        return $this->availableFilters;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function getAvailableOrders(): ?array
    {
        return $this->availableOrders;
    }
}
