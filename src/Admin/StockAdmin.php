<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Stock;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Stock|null getSubject()
 */
class StockAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('create');
        $collection->remove('delete');
        parent::configureRoutes($collection);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('book.name', null, ['label' => 'BOOK_ENTITY.LABEL.NAME']);
        $filter->add('book.description', null, ['label' => 'BOOK_ENTITY.LABEL.DESCRIPTION']);
        $filter->add('quantity', null, ['label' => 'STOCK_ENTITY.LABEL.QUANTITY']);
        $filter->add('reserved', null, ['label' => 'STOCK_ENTITY.LABEL.RESERVED']);
        $filter->add('book.authors', null, ['label' => 'BOOK_ENTITY.LABEL.AUTHORS']);
        $filter->add('book.categories', null, ['label' => 'BOOK_ENTITY.LABEL.CATEGORIES']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'book.name', 'BOOK_ENTITY.LABEL.NAME');
        $this->configureListFieldText($list, 'book.description', 'BOOK_ENTITY.LABEL.DESCRIPTION');
        $this->configureListFieldText($list, 'quantity', 'STOCK_ENTITY.LABEL.QUANTITY');
        $this->configureListFieldText($list, 'reserved', 'STOCK_ENTITY.LABEL.RESERVED');
        $this->configureListFieldText($list, 'book.authors', 'BOOK_ENTITY.LABEL.AUTHORS');
        $this->configureListFieldText($list, 'book.categories', 'BOOK_ENTITY.LABEL.CATEGORIES');
        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('STOCK_ENTITY.SECTION.MAIN');

        $this->configureFormFieldNumber(
            $form,
            'quantity',
            'STOCK_ENTITY.LABEL.QUANTITY',
            'STOCK_ENTITY.HELP.QUANTITY',
            false,
            ['constraints' => [new NotBlank(), new GreaterThanOrEqual(0)]]
        );

        $this->configureFormFieldNumber(
            $form,
            'reserved',
            'STOCK_ENTITY.LABEL.RESERVED',
            'STOCK_ENTITY.HELP.RESERVED',
            false,
            [
                'mapped' => false,
                'data'   => $this->getSubject()?->getReserved(),
                'attr'   => ['readonly' => true, 'disabled' => true],
            ]
        );

        $form->end();
    }

}
