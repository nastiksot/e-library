<?php

declare(strict_types=1);

namespace App\DTO\Paginator;

use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginatorMetaDTO extends AbstractPaginatorDTO
{
    protected int $total        = 0;
    protected int $currentPage  = 1;
    protected int $itemsPerPage = 5;
    protected int $proximity    = 3;
    protected int $maxPage      = 1;

    public function __construct(int $total, int $currentPage = 1, int $itemsPerPage = 10, int $maxPage = 1)
    {
        parent::__construct(
            [
                'total'        => $total,
                'currentPage'  => $currentPage,
                'itemsPerPage' => $itemsPerPage,
                'maxPage'      => $maxPage,
                'proximity'    => 3,
            ]
        );
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setAllowedTypes('total', ['integer']);
        $resolver->setAllowedTypes('currentPage', ['integer', 'null']);
        $resolver->setAllowedTypes('itemsPerPage', ['integer', 'null']);
        $resolver->setAllowedTypes('proximity', ['integer', 'null']);
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getCurrentPage(): ?int
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): ?int
    {
        return $this->itemsPerPage;
    }

    public function getProximity(): ?int
    {
        return $this->proximity;
    }

    public function getMaxPage(): ?int
    {
        return $this->maxPage;
    }
}
