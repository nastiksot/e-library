<?php

declare(strict_types=1);

namespace App\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

class ReadingProlongAdmin extends ReadingAdmin
{
    protected function getAccessMapping(): array
    {
        return [
            'prolong_accept' => 'PROLONG_ACCEPT',
            'prolong_cancel' => 'PROLONG_CANCEL',
        ];
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->add('prolong_accept', $this->getRouterIdParameter() . '/prolong-accept');
        $collection->add('prolong_cancel', $this->getRouterIdParameter() . '/prolong-cancel');
        $collection->remove('create');
        parent::configureRoutes($collection);
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        /** @var ProxyQueryInterface|QueryBuilder $query */
        $query = parent::configureQuery($query);
        $alias = current($query->getRootAliases());
        $query->andWhere($query->expr()->isNotNull("{$alias}.prolongAt"));

        return $query;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'order.id', 'READING_ENTITY.LABEL.ORDER_ID');
        $this->configureListFieldText($list, 'readingType', 'READING_ENTITY.LABEL.READING_TYPE');
        $this->configureListFieldText($list, 'book', 'READING_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'quantity', 'READING_ENTITY.LABEL.QUANTITY');
        $this->configureListFieldText($list, 'user', 'READING_ENTITY.LABEL.USER', ['admin_code' => 'admin.user']);
        $this->configureListFieldDate($list, 'startAt', 'READING_ENTITY.LABEL.START_AT');

        $this->configureListFieldDate(
            $list,
            'endAt',
            'READING_ENTITY.LABEL.END_AT',
            ['template' => 'admin/reading/list__field_end_at.html.twig']
        );
        $this->configureListFieldText(
            $list,
            'penalty',
            'READING_ENTITY.LABEL.PENALTY',
            ['template' => 'admin/reading/list__field_penalty.html.twig']
        );
        $this->configureListFieldDate(
            $list,
            'prolongAt',
            'READING_ENTITY.LABEL.PROLONG_AT',
            ['template' => 'admin/reading/list__field_prolong_at.html.twig']
        );


        $actions = [
            'prolong_accept' => ['template' => 'admin/reading/list__action_prolong_accept.html.twig'],
            'prolong_cancel' => ['template' => 'admin/reading/list__action_prolong_cancel.html.twig'],
        ];
        $this->configureListFieldActions($list, $actions);
    }
}
