<?php

declare(strict_types=1);

namespace App\Admin;

use Carbon\Carbon;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

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
//    protected function configureRoutes(RouteCollectionInterface $collection): void
//    {
//        $collection->add('prolong_accept', $this->getRouterIdParameter() . '/prolong-accept');
//        $collection->add('prolong_cancel', $this->getRouterIdParameter() . '/prolong-cancel');
//        parent::configureRoutes($collection);
//    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        $expireDay = Carbon::today()->startOfDay();
        $expireDay = Carbon::tomorrow()->startOfDay();
        /** @var ProxyQueryInterface|QueryBuilder $query */
        $query = parent::configureQuery($query);
        $alias = current($query->getRootAliases());
//        $query->andWhere($query->expr()->isNotNull("{$alias}.prolongAt"));
        $query->andWhere("{$alias}.endAt <= :expireDay");
        $query->setParameter(':expireDay', $expireDay);

//        reading.end_at < NOW()
        return $query;
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
