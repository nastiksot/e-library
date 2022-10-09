<?php

declare(strict_types=1);

namespace App\Admin\User\Profile;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Order;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Order|null getSubject()
 */
class OrderUserProfileAdmin extends AbstractUserProfileAdmin
{
    use ConfigureAdminFullTrait;

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('book', null, ['label' => 'ORDER_ENTITY.LABEL.BOOK']);
        $filter->add('user', null, ['label' => 'ORDER_ENTITY.LABEL.USER'], ['admin_code' => 'admin.user']);
        $filter->add('quantity', null, ['label' => 'ORDER_ENTITY.LABEL.QUANTITY']);
//        $this->configureFilterFieldCreatedAtDateRange($filter);
//        $this->configureFilterFieldUpdatedAtDateRange($filter);
        $this->configureFilterFieldDateRange($filter, 'startAt', 'ORDER_ENTITY.LABEL.START_AT');
        $this->configureFilterFieldDateRange($filter, 'endAt', 'ORDER_ENTITY.LABEL.END_AT');
//        $this->configureFilterFieldDateRange($filter, 'prolongAt', 'ORDER_ENTITY.LABEL.PROLONG_AT');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'book', 'ORDER_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'user', 'ORDER_ENTITY.LABEL.USER', ['admin_code' => 'admin.user']);
//        $this->configureListFieldCreatedAt($list);
//        $this->configureListFieldUpdatedAt($list);
        $this->configureListFieldDate($list, 'startAt', 'ORDER_ENTITY.LABEL.START_AT');
        $this->configureListFieldDate($list, 'endAt', 'ORDER_ENTITY.LABEL.END_AT');
//        $this->configureListFieldDate($list, 'prolongAt', 'ORDER_ENTITY.LABEL.PROLONG_AT');

        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('ORDER_ENTITY.SECTION.MAIN');

        $form
            ->add(
                'book',
                ModelType::class,
                [
                    'label'       => 'ORDER_ENTITY.LABEL.BOOK',
                    'help'        => 'ORDER_ENTITY.HELP.BOOK',
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
                    'label'       => 'ORDER_ENTITY.LABEL.USER',
                    'help'        => 'ORDER_ENTITY.HELP.USER',
                    'required'    => false,
                    'btn_add'     => false,
                    'constraints' => [new NotBlank()],
                ],
                ['admin_code' => 'admin.user']
            );

        $this->configureFormFieldNumber(
            $form,
            'quantity',
            'ORDER_ENTITY.LABEL.QUANTITY',
            'ORDER_ENTITY.HELP.QUANTITY',
            false,
            ['constraints' => [new NotBlank()]]
        );

        $this->configureFormFieldDate(
            $form,
            'startAt',
            'ORDER_ENTITY.LABEL.START_AT',
            'ORDER_ENTITY.HELP.START_AT',
            false
        );

        $this->configureFormFieldDate(
            $form,
            'endAt',
            'ORDER_ENTITY.LABEL.END_AT',
            'ORDER_ENTITY.HELP.END_AT',
            false
        );

//        $this->configureFormFieldDate(
//            $form,
//            'prolongAt',
//            'ORDER_ENTITY.LABEL.PROLONG_AT',
//            'ORDER_ENTITY.HELP.PROLONG_AT',
//            false
//        );

        $form->end();
    }

}
