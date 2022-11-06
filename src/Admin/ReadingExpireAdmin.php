<?php

declare(strict_types=1);

namespace App\Admin;

use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Filter\Model\FilterData;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Sonata\Form\Type\DateRangeType;

class ReadingExpireAdmin extends ReadingAdmin
{
//    protected function getAccessMapping(): array
//    {
//        return [
//            'prolong_accept' => 'PROLONG_ACCEPT',
//            'prolong_cancel' => 'PROLONG_CANCEL',
//        ];
//    }
//
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
//        $collection->add('prolong_accept', $this->getRouterIdParameter() . '/prolong-accept');
//        $collection->add('prolong_cancel', $this->getRouterIdParameter() . '/prolong-cancel');
        $collection->remove('create');
        parent::configureRoutes($collection);
    }


    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add(
            'expireAt',
            CallbackFilter::class,
            [
                'callback'      => [$this, 'configureQueryExpireAtCallbackFilter'],
                'label'         => 'READING_ENTITY.LABEL.EXPIRE_AT',
                'field_type'    => DateRangeType::class,
                'field_options' => [
                    'field_options'       => [
                        'widget' => 'single_text',
                    ],
//                    'field_options_start' => [
//                        'attr' => ['max' => Carbon::today()->format('Y-m-d')],
//                    ],
//                    'field_options_end'   => [
//                        'attr' => ['min' => Carbon::today()->format('Y-m-d')],
//                    ],
                ],
            ],
        );

        parent::configureDatagridFilters($filter);
    }

    /**
     * Configures a list of default filters.
     *
     * @param array<string, array<string, mixed>> $filterValues
     */
    protected function configureDefaultFilterValues(array &$filterValues): void
    {
        $expireDay                = Carbon::today()->startOfDay();
        $filterValues['expireAt'] = ['value' => ['end' => $expireDay->format('Y-m-d')]];
    }

    public function configureQueryExpireAtCallbackFilter(
        ProxyQueryInterface|QueryBuilder $query,
        string $alias,
        string $field,
        FilterData $data
    ): bool {
        if (!$data->hasValue()) {
            return false;
        }

        // filter value
        $value         = $data->getValue();
        $expireStartAt = $value['start'] instanceof DateTimeInterface ? Carbon::instance($value['start']) : null;
        $expireEndAt   = $value['end'] instanceof DateTimeInterface ? Carbon::instance($value['end']) : null;

        // $expireStartAt update query
        if ($expireStartAt !== null) {
            $query
                ->andWhere("{$alias}.endAt >= :expireStartAt")
                ->setParameter('expireStartAt', $expireStartAt);
        }

        // $expireEndAt update query
        if ($expireEndAt !== null) {
            $query
                ->andWhere("{$alias}.endAt <= :expireEndAt")
                ->setParameter('expireEndAt', $expireEndAt);
        }

        return true;
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

//        $actions = [
//            'prolong_accept' => ['template' => 'admin/reading/list__action_prolong_accept.html.twig'],
//            'prolong_cancel' => ['template' => 'admin/reading/list__action_prolong_cancel.html.twig'],
//        ];
//        $this->configureListFieldActions($list, $actions);
    }
}
