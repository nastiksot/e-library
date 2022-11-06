<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Admin\Traits\OrderStatusChoicesTrait;
use App\Admin\Traits\ReadingTypeChoicesTrait;
use App\Contracts\Dictionary\DecisionAction;
use App\Contracts\Dictionary\OrderStatus;
use App\CQ\Command\Stock\ReserveCancelStockCommand;
use App\CQ\Command\Stock\ReserveRestoreStockCommand;
use App\Entity\Order;
use Carbon\Carbon;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Order|null getSubject()
 */
class OrderAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;
    use OrderStatusChoicesTrait;
    use ReadingTypeChoicesTrait;

    /**
     * @param Order $object
     */
    protected function preRemove(object $object): void
    {
        if (OrderStatus::OPEN()->getValue() === $object->getStatus()->getValue()) {
            $this->messageBusHandler->handleCommand(
                new ReserveCancelStockCommand(
                    $object->getBook()->getId(),
                    $object->getQuantity()
                )
            );
        }

        if (OrderStatus::DONE()->getValue() === $object->getStatus()->getValue()) {
            $this->messageBusHandler->handleCommand(
                new ReserveRestoreStockCommand(
                    $object->getBook()->getId(),
                    $object->getQuantity()
                )
            );
        }
    }

    protected function getAccessMapping(): array
    {
        return [
            'done'   => 'DONE',
            'cancel' => 'CANCEL',
        ];
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->add('done', $this->getRouterIdParameter() . '/done');
        $collection->add('cancel', $this->getRouterIdParameter() . '/cancel');
        $collection->remove('create');
        parent::configureRoutes($collection);
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues = [
            '_sort_by'    => 'id',
            '_sort_order' => 'DESC',
        ];
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('id', null, ['label' => 'ORDER_ENTITY.LABEL.ID']);
        $this->configureFilterFieldChoice(
            $filter,
            'status',
            $this->getOrderStatusChoices(),
            'ORDER_ENTITY.LABEL.STATUS'
        );

        $this->configureFilterFieldChoice(
            $filter,
            'readingType',
            $this->getReadingTypeChoices(),
            'READING_ENTITY.LABEL.READING_TYPE'
        );

        $filter->add('book', null, ['label' => 'ORDER_ENTITY.LABEL.BOOK']);
        $filter->add('book.categories', null, ['label' => 'BOOK_ENTITY.LABEL.CATEGORIES']);
        $filter->add('quantity', null, ['label' => 'ORDER_ENTITY.LABEL.QUANTITY']);
        $filter->add('user', null, ['label' => 'ORDER_ENTITY.LABEL.USER'], ['admin_code' => 'admin.user']);
        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
        $this->configureFilterFieldDateRange($filter, 'startAt', 'ORDER_ENTITY.LABEL.START_AT');
        $this->configureFilterFieldDateRange($filter, 'endAt', 'ORDER_ENTITY.LABEL.END_AT');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldCreatedAt($list);
        $this->configureListFieldText($list, 'status', 'ORDER_ENTITY.LABEL.STATUS');
        $this->configureListFieldText($list, 'readingType', 'READING_ENTITY.LABEL.READING_TYPE');
        $this->configureListFieldUpdatedAt($list);
        $this->configureListFieldText($list, 'book', 'ORDER_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'quantity', 'ORDER_ENTITY.LABEL.QUANTITY');
        $this->configureListFieldText($list, 'user', 'ORDER_ENTITY.LABEL.USER', ['admin_code' => 'admin.user']);
        $this->configureListFieldDate($list, 'startAt', 'ORDER_ENTITY.LABEL.START_AT');
        $this->configureListFieldDate($list, 'endAt', 'ORDER_ENTITY.LABEL.END_AT');

        $handle = [
            'done'   => ['template' => 'admin/order/list__action_done.html.twig'],
            'cancel' => ['template' => 'admin/order/list__action_cancel.html.twig'],
        ];

        $list->add(
            '_handle',
            ListMapper::TYPE_ACTIONS,
            [
                'actions'      => $handle,
                'label'        => 'Handle',
                'header_style' => 'min-width:170px;',
            ],
        );

        $actions = [
            'edit'   => ['template' => 'admin/order/list__action_edit.html.twig'],
            'delete' => [],
        ];
        $this->configureListFieldActions($list, $actions);
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
            ['constraints' => [new NotBlank(), new GreaterThanOrEqual(1)]]
        );

        $this->configureFormFieldChoice(
            $form,
            'readingType',
            $this->getReadingTypeChoices(),
            'ORDER_ENTITY.LABEL.READING_TYPE',
            'ORDER_ENTITY.HELP.READING_TYPE',
            true,
        );

        $this->configureFormFieldDateType(
            $form,
            'startAt',
            'ORDER_ENTITY.LABEL.START_AT',
            'ORDER_ENTITY.HELP.START_AT',
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
            'ORDER_ENTITY.LABEL.END_AT',
            'ORDER_ENTITY.HELP.END_AT',
            false,
            [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                ],
            ]
        );

        $form->end();
    }

}
