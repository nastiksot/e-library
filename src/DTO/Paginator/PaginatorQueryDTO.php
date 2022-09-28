<?php

declare(strict_types=1);

namespace App\DTO\Paginator;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginatorQueryDTO extends AbstractPaginatorDTO
{
    protected Paginator $paginator;
    protected PaginatorMetaDTO $meta;

    public function __construct(Paginator $paginator, PaginatorMetaDTO $meta)
    {
        parent::__construct(['paginator' => $paginator, 'meta' => $meta]);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setAllowedTypes('paginator', [Paginator::class]);
        $resolver->setAllowedTypes('meta', [PaginatorMetaDTO::class]);
    }

    public function getPaginator(): Paginator
    {
        return $this->paginator;
    }

    public function getMeta(): PaginatorMetaDTO
    {
        return $this->meta;
    }
}
