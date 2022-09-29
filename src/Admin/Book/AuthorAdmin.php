<?php

declare(strict_types=1);

namespace App\Admin\Book;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Book\Author;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Author|null getSubject()
 */
class AuthorAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('firstName', null, ['label' => 'AUTHOR_ENTITY.LABEL.FIRST_NAME']);
        $filter->add('lastName', null, ['label' => 'AUTHOR_ENTITY.LABEL.LAST_NAME']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'firstName', 'AUTHOR_ENTITY.LABEL.FIRST_NAME');
        $this->configureListFieldText($list, 'lastName', 'AUTHOR_ENTITY.LABEL.LAST_NAME');

        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('AUTHOR_ENTITY.SECTION.MAIN');
        $this->configureFormFieldText(
            $form,
            'firstName',
            'AUTHOR_ENTITY.LABEL.FIRST_NAME',
            null,
            false,
            ['constraints' => [new NotBlank()]]
        );

        $this->configureFormFieldText(
            $form,
            'lastName',
            'AUTHOR_ENTITY.LABEL.LAST_NAME',
            null,
            false,
            ['constraints' => [new NotBlank()]]
        );
        $form->end();
    }

}
