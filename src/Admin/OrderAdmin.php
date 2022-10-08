<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Contracts\Dictionary\DecisionAction;
use App\Contracts\Dictionary\OrderStatus;
use App\Entity\Order;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Order|null getSubject()
 */
class OrderAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('book', null, ['label' => 'ORDER_ENTITY.LABEL.BOOK']);
        $filter->add('user', null, ['label' => 'ORDER_ENTITY.LABEL.USER'], ['admin_code' => 'admin.user']);
        $filter->add('quantity', null, ['label' => 'ORDER_ENTITY.LABEL.QUANTITY']);
        $this->configureFilterFieldChoice($filter, 'status', $this->getStatusChoices(), 'ORDER_ENTITY.LABEL.STATUS');
        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'book', 'ORDER_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'user', 'ORDER_ENTITY.LABEL.USER', ['admin_code' => 'admin.user']);
        $this->configureListFieldText($list, 'quantity', 'ORDER_ENTITY.LABEL.QUANTITY');
        $this->configureListFieldText($list, 'status', 'ORDER_ENTITY.LABEL.STATUS');
//        $this->configureListFieldCreatedAt($list);
//        $this->configureListFieldUpdatedAt($list);
//        $this->configureListFieldDate($list, 'startAt', 'ORDER_ENTITY.LABEL.START_AT');
//        $this->configureListFieldDate($list, 'endAt', 'ORDER_ENTITY.LABEL.END_AT');
//        $this->configureListFieldDate($list, 'prolongAt', 'ORDER_ENTITY.LABEL.PROLONG_AT');

        $this->configureListFieldActions($list);
    }


    private function getStatusChoices(): array
    {
        $choices = [];

        foreach (OrderStatus::toArray() as $key => $value) {
            $choices['ORDER_ENTITY.CHOICES.STATUS.' . $key] = $value;
        }

        return $choices;
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

        $this->configureFormFieldChoice(
            $form,
            'status',
            $this->getStatusChoices(),
            'ORDER_ENTITY.LABEL.STATUS',
            'ORDER_ENTITY.HELP.STATUS',
            true,
        );

//        $this->configureFormFieldDate(
//            $form,
//            'startAt',
//            'ORDER_ENTITY.LABEL.START_AT',
//            'ORDER_ENTITY.HELP.START_AT',
//            false
//        );
//
//        $this->configureFormFieldDate(
//            $form,
//            'endAt',
//            'ORDER_ENTITY.LABEL.END_AT',
//            'ORDER_ENTITY.HELP.END_AT',
//            false
//        );

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
