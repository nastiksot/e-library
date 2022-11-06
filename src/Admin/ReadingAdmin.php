<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Admin\Traits\OrderStatusChoicesTrait;
use App\Admin\Traits\ReadingTypeChoicesTrait;
use App\Entity\Reading;
use Carbon\Carbon;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Reading|null getSubject()
 */
class ReadingAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;
    use OrderStatusChoicesTrait;
    use ReadingTypeChoicesTrait;

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues = [
            '_sort_by'    => 'id',
            '_sort_order' => 'DESC',
        ];
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('order.id', null, ['label' => 'READING_ENTITY.LABEL.ORDER_ID']);
        $this->configureFilterFieldChoice(
            $filter,
            'readingType',
            $this->getReadingTypeChoices(),
            'READING_ENTITY.LABEL.READING_TYPE'
        );

        $filter->add('book', null, ['label' => 'READING_ENTITY.LABEL.BOOK']);
        $filter->add('book.categories', null, ['label' => 'BOOK_ENTITY.LABEL.CATEGORIES']);
        $filter->add('quantity', null, ['label' => 'READING_ENTITY.LABEL.QUANTITY']);
        $filter->add('user', null, ['label' => 'READING_ENTITY.LABEL.USER'], ['admin_code' => 'admin.user']);
        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
        $this->configureFilterFieldDateRange($filter, 'startAt', 'READING_ENTITY.LABEL.START_AT');
        $this->configureFilterFieldDateRange($filter, 'endAt', 'READING_ENTITY.LABEL.END_AT');
        $this->configureFilterFieldDateRange($filter, 'prolongAt', 'READING_ENTITY.LABEL.PROLONG_AT');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldCreatedAt($list);
        $this->configureListFieldText($list, 'order.id', 'READING_ENTITY.LABEL.ORDER_ID');
        $this->configureListFieldText($list, 'readingType', 'READING_ENTITY.LABEL.READING_TYPE');
        $this->configureListFieldUpdatedAt($list);
        $this->configureListFieldText($list, 'book', 'READING_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'quantity', 'READING_ENTITY.LABEL.QUANTITY');
        $this->configureListFieldText($list, 'user', 'READING_ENTITY.LABEL.USER', ['admin_code' => 'admin.user']);
        $this->configureListFieldDate($list, 'startAt', 'READING_ENTITY.LABEL.START_AT');
        $this->configureListFieldDate($list, 'endAt', 'READING_ENTITY.LABEL.END_AT');
        $this->configureListFieldDate($list, 'prolongAt', 'READING_ENTITY.LABEL.PROLONG_AT');

        $actions = [
            'edit'   => ['template' => 'admin/reading/list__action_edit.html.twig'],
            'delete' => [],
        ];
        $this->configureListFieldActions($list, $actions);
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
            ['constraints' => [new NotBlank(), new GreaterThanOrEqual(1)]]
        );

        $this->configureFormFieldChoice(
            $form,
            'readingType',
            $this->getReadingTypeChoices(),
            'READING_ENTITY.LABEL.READING_TYPE',
            'READING_ENTITY.HELP.READING_TYPE',
            true,
        );

        $this->configureFormFieldDateType(
            $form,
            'startAt',
            'READING_ENTITY.LABEL.START_AT',
            'READING_ENTITY.HELP.START_AT',
            false,
            [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                ],
            ]
        );

        $this->configureFormFieldDateType(
            $form,
            'endAt',
            'READING_ENTITY.LABEL.END_AT',
            'READING_ENTITY.HELP.END_AT',
            false,
            [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                ],
            ]
        );

        $this->configureFormFieldDateType(
            $form,
            'prolongAt',
            'READING_ENTITY.LABEL.PROLONG_AT',
            'READING_ENTITY.HELP.PROLONG_AT'
        );

        $form->end();
    }

}
