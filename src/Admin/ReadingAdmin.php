<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Admin\Traits\OrderStatusChoicesTrait;
use App\Admin\Traits\ReadingTypeChoicesTrait;
use App\CQ\Command\Stock\ReserveAddStockCommand;
use App\CQ\Command\Stock\ReserveDoneStockCommand;
use App\CQ\Command\Stock\ReserveRestoreStockCommand;
use App\CQ\Query\Reading\GetTotalPenaltyReadingQuery;
use App\CQ\Query\Stock\GetStockQuery;
use App\Entity\Reading;
use App\Entity\Stock;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method Reading|null getSubject()
 */
class ReadingAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;
    use OrderStatusChoicesTrait;
    use ReadingTypeChoicesTrait;

    /**
     * @param Reading $object
     */
    protected function postPersist(object $object): void
    {
        $this->messageBusHandler->handleCommand(
            new ReserveAddStockCommand(
                (int)$object->getBook()?->getId(),
                $object->getQuantity()
            )
        );

        $this->messageBusHandler->handleCommand(
            new ReserveDoneStockCommand(
                (int)$object->getBook()?->getId(),
                $object->getQuantity()
            )
        );
    }

    /**
     * @param Reading $object
     */
    protected function preRemove(object $object): void
    {
        // remove order together with reading
        if ($object->getOrder()) {
            $this->em->remove($object->getOrder());
        }

        $this->messageBusHandler->handleCommand(
            new ReserveRestoreStockCommand(
                (int)$object->getBook()?->getId(),
                $object->getQuantity()
            )
        );
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

    protected function configureListFieldStartAt(
        ListMapper $list,
        ?string $name = 'startAt',
        ?string $label = 'READING_ENTITY.LABEL.START_AT',
        ?array $options = []
    ): void {
        $this->configureListFieldDate($list, $name, $label, $options);
    }

    protected function configureListFieldEndAt(
        ListMapper $list,
        ?string $name = 'endAt',
        ?string $label = 'READING_ENTITY.LABEL.END_AT',
        ?array $options = []
    ): void {
        $this->configureListFieldDate(
            $list,
            $name,
            $label,
            array_merge(
                [
                    'template' => 'admin/reading/list__field_end_at.html.twig',
                ],
                $options
            )
        );
    }

    protected function configureListFieldProlongAt(
        ListMapper $list,
        ?string $name = 'prolongAt',
        ?string $label = 'READING_ENTITY.LABEL.PROLONG_AT',
        ?array $options = []
    ): void {
        $this->configureListFieldDate(
            $list,
            $name,
            $label,
            array_merge(
                [
                    'template' => 'admin/reading/list__field_prolong_at.html.twig',
                ],
                $options
            )
        );
    }

    protected function configureListFieldPenalty(
        ListMapper $list,
        ?string $name = 'penalty',
        ?string $label = 'READING_ENTITY.LABEL.PENALTY',
        ?array $options = []
    ): void {
        $this->configureListFieldDate(
            $list,
            $name,
            $label,
            array_merge(
                [
                    'template' => 'admin/reading/list__field_penalty.html.twig',
                    'data'     => $this->listTemplateData(),
                ],
                $options
            )
        );
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'order.id', 'READING_ENTITY.LABEL.ORDER_ID');
        $this->configureListFieldText($list, 'readingType', 'READING_ENTITY.LABEL.READING_TYPE');
        $this->configureListFieldText($list, 'book', 'READING_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'quantity', 'READING_ENTITY.LABEL.QUANTITY');
        $this->configureListFieldText($list, 'user', 'READING_ENTITY.LABEL.USER', ['admin_code' => 'admin.user']);

        $this->configureListFieldStartAt($list);
        $this->configureListFieldEndAt($list);
        $this->configureListFieldPenalty($list);
        $this->configureListFieldProlongAt($list);

        $actions = [
            'edit'   => ['template' => 'admin/reading/list__action_edit.html.twig'],
            'delete' => [],
        ];
        $this->configureListFieldActions($list, $actions);
    }

    private function listTemplateData(): array
    {
        /** @var ProxyQueryInterface|QueryBuilder $query */
        $qb    = $this->createQuery();
        $alias = current($qb->getRootAliases());

        // collect all ids that were displayed by filtering
        $ids = $qb->select("{$alias}.id")->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN);

        // get total penalty for displayed data grid
        $totalPenalty = $this->messageBusHandler->handleQuery(new GetTotalPenaltyReadingQuery(readingIds: $ids));

        return [
            'total_penalty' => $totalPenalty,
        ];
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

        $quantityOptions = $this->getSubject()?->getId()
            ? [
                'mapped' => false,
                //'data'   => $this->getSubject()?->getQuantity(),
                'attr'   => ['readonly' => true, 'disabled' => true, 'value' => $this->getSubject()?->getQuantity()],
            ]
            : [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(1),
                    new Callback([$this, 'validateFormFieldQuantity']),
                ],
            ];

        $this->configureFormFieldNumber(
            $form,
            'quantity',
            'READING_ENTITY.LABEL.QUANTITY',
            'READING_ENTITY.HELP.QUANTITY',
            false,
            $quantityOptions
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
                'attr'        => ['data-stored' => $this->getSubject()?->getStartAt()?->format('Y-m-d')],
                'constraints' => [new NotBlank()],
            ]
        );

        $this->configureFormFieldDateType(
            $form,
            'endAt',
            'READING_ENTITY.LABEL.END_AT',
            'READING_ENTITY.HELP.END_AT',
            false,
            [
                'attr'        => ['data-stored' => $this->getSubject()?->getEndAt()?->format('Y-m-d')],
                'constraints' => [new NotBlank()],
            ]
        );

        $this->configureFormFieldDateType(
            $form,
            'prolongAt',
            'READING_ENTITY.LABEL.PROLONG_AT',
            'READING_ENTITY.HELP.PROLONG_AT',
            false,
            [
                'attr' => ['data-stored' => $this->getSubject()?->getProlongAt()?->format('Y-m-d')],
            ]
        );

        $form->end();
    }

    public function validateFormFieldQuantity(int $quantity, ExecutionContextInterface $context): void
    {
        $reading = $this->getSubject();

        if ($quantity > 0 && $reading && $reading->getBook()) {
            /** @var Stock $stock */
            $stock = $this->messageBusHandler->handleQuery(new GetStockQuery($reading->getBook()->getId()));

            if ($quantity > $stock->getQuantity()) {
                $context
                    ->buildViolation('STOCK_ENTITY.MESSAGE.OUT_OF_STOCK.QUANTITY')
                    ->setParameter('%quantity%', (string)$quantity)
                    ->setParameter('%stock%', (string)$stock->getQuantity())
                    ->setTranslationDomain($this->getTranslationDomain())
                    ->atPath('quantity')->addViolation();
            }
        }
    }

}
