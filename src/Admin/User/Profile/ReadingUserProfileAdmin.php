<?php

declare(strict_types=1);

namespace App\Admin\User\Profile;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Reading;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Reading|null getSubject()
 */
class ReadingUserProfileAdmin extends AbstractUserProfileAdmin
{
    use ConfigureAdminFullTrait;

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('book', null, ['label' => 'READING_ENTITY.LABEL.BOOK']);
        $filter->add('user', null, ['label' => 'READING_ENTITY.LABEL.USER'], ['admin_code' => 'admin.user']);
        $filter->add('quantity', null, ['label' => 'READING_ENTITY.LABEL.QUANTITY']);
//        $this->configureFilterFieldCreatedAtDateRange($filter);
//        $this->configureFilterFieldUpdatedAtDateRange($filter);
        $this->configureFilterFieldDateRange($filter, 'startAt', 'READING_ENTITY.LABEL.START_AT');
        $this->configureFilterFieldDateRange($filter, 'endAt', 'READING_ENTITY.LABEL.END_AT');
        $this->configureFilterFieldDateRange($filter, 'prolongAt', 'READING_ENTITY.LABEL.PROLONG_AT');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'book', 'READING_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'user', 'READING_ENTITY.LABEL.USER', ['admin_code' => 'admin.user']);
//        $this->configureListFieldCreatedAt($list);
//        $this->configureListFieldUpdatedAt($list);
        $this->configureListFieldDate($list, 'startAt', 'READING_ENTITY.LABEL.START_AT');
        $this->configureListFieldDate($list, 'endAt', 'READING_ENTITY.LABEL.END_AT');
        $this->configureListFieldDate($list, 'prolongAt', 'READING_ENTITY.LABEL.PROLONG_AT');

        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('READING_ENTITY.SECTION.MAIN');

        $form
            ->add(
                'book',
                ModelType::class,
                [
                    'label'       => 'READING_ENTITY.LABEL.BOOK',
                    'help'        => 'READING_ENTITY.HELP.BOOK',
                    'required'    => false,
                    'btn_add'     => false,
                    'constraints' => [new NotBlank()],
                ],
                ['admin_code' => 'admin.book']
            );

        $form
            ->add(
                'user',
                ModelType::class,
                [
                    'label'       => 'READING_ENTITY.LABEL.USER',
                    'help'        => 'READING_ENTITY.HELP.USER',
                    'required'    => false,
                    'btn_add'     => false,
                    'constraints' => [new NotBlank()],
                ],
                ['admin_code' => 'admin.user']
            );

        $this->configureFormFieldNumber(
            $form,
            'quantity',
            'READING_ENTITY.LABEL.QUANTITY',
            'READING_ENTITY.HELP.QUANTITY',
            false,
            ['constraints' => [new NotBlank()]]
        );

        $this->configureFormFieldDate(
            $form,
            'startAt',
            'READING_ENTITY.LABEL.START_AT',
            'READING_ENTITY.HELP.START_AT',
            false
        );

        $this->configureFormFieldDate(
            $form,
            'endAt',
            'READING_ENTITY.LABEL.END_AT',
            'READING_ENTITY.HELP.END_AT',
            false
        );

        $this->configureFormFieldDate(
            $form,
            'prolongAt',
            'READING_ENTITY.LABEL.PROLONG_AT',
            'READING_ENTITY.HELP.PROLONG_AT',
            false
        );

        $form->end();
    }

}
