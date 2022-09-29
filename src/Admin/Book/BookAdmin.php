<?php

declare(strict_types=1);

namespace App\Admin\Book;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Book\Book;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Book|null getSubject()
 */
class BookAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('name', null, ['label' => 'BOOK_ENTITY.LABEL.NAME']);
        $filter->add('description', null, ['label' => 'BOOK_ENTITY.LABEL.DESCRIPTION']);
        $filter->add('quantity', null, ['label' => 'BOOK_ENTITY.LABEL.QUANTITY']);
        $filter->add('authors', null, ['label' => 'BOOK_ENTITY.LABEL.AUTHORS']);
        $filter->add('categories', null, ['label' => 'BOOK_ENTITY.LABEL.CATEGORIES']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'name', 'BOOK_ENTITY.LABEL.NAME');
        $this->configureListFieldText($list, 'description', 'BOOK_ENTITY.LABEL.DESCRIPTION');
        $this->configureListFieldText($list, 'quantity', 'BOOK_ENTITY.LABEL.QUANTITY');
        $this->configureListFieldText($list, 'authors', 'BOOK_ENTITY.LABEL.AUTHORS');
        $this->configureListFieldText($list, 'categories', 'BOOK_ENTITY.LABEL.CATEGORIES');

        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('BOOK_ENTITY.SECTION.MAIN');
        $this->configureFormFieldText(
            $form,
            'name',
            'BOOK_ENTITY.LABEL.NAME',
            'BOOK_ENTITY.HELP.NAME',
            false,
            ['constraints' => [new NotBlank()]]
        );

        $this->configureFormFieldText(
            $form,
            'description',
            'BOOK_ENTITY.LABEL.DESCRIPTION',
            'BOOK_ENTITY.HELP.DESCRIPTION',
            false,
            ['constraints' => [new NotBlank()]]
        );

        $this->configureFormFieldNumber(
            $form,
            'quantity',
            'BOOK_ENTITY.LABEL.QUANTITY',
            'BOOK_ENTITY.HELP.QUANTITY',
            false,
            ['constraints' => [new NotBlank()]]
        );

        $form->add(
            'authors',
            null,
            [
                'by_reference' => false,
                'label'        => 'BOOK_ENTITY.LABEL.AUTHORS',
                'help'         => 'BOOK_ENTITY.HELP.AUTHORS',
            ]
        );

        $form->add(
            'categories',
            null,
            [
                'by_reference' => false,
                'label'        => 'BOOK_ENTITY.LABEL.CATEGORIES',
                'help'         => 'BOOK_ENTITY.HELP.CATEGORIES',
            ]
        );


        $form->end();
    }

}
