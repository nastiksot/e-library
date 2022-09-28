<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\MailLog;
use JetBrains\PhpStorm\ArrayShape;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

/**
 * @method MailLog|null getSubject()
 */
class MailLogAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues = [
            '_sort_order' => 'DESC',
            '_sort_by'    => 'createdAt',
        ];
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        //parent::configureRoutes($collection);
        $collection->clearExcept(['show', 'list']);
    }

    #[ArrayShape([
        'MAIL_LOG_ENTITY.STATUS.SENT'  => 'string',
        'MAIL_LOG_ENTITY.STATUS.ERROR' => 'string',
    ])]
    protected function getStatusChoices(): array
    {
        return [
            // 'MAIL_LOG_ENTITY.STATUS.PENDING' => MailLog::STATUS_PENDING,
            'MAIL_LOG_ENTITY.STATUS.SENT'  => MailLog::STATUS_SENT,
            'MAIL_LOG_ENTITY.STATUS.ERROR' => MailLog::STATUS_ERROR,
        ];
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $this->configureFilterFieldChoice($filter, 'status', $this->getStatusChoices(), 'MAIL_LOG_ENTITY.LABEL.STATUS');
        $this->configureFilterFieldText($filter, 'replyTo', 'MAIL_LOG_ENTITY.LABEL.REPLY_TO');
        $this->configureFilterFieldText($filter, 'to', 'MAIL_LOG_ENTITY.LABEL.TO');
        $this->configureFilterFieldText($filter, 'subject', 'MAIL_LOG_ENTITY.LABEL.SUBJECT');
        $this->configureFilterFieldText($filter, 'message', 'MAIL_LOG_ENTITY.LABEL.MESSAGE');
        $this->configureFilterFieldCreatedAtDateRange($filter);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'status', 'MAIL_LOG_ENTITY.LABEL.STATUS', ['identifier' => true]);
        $this->configureListFieldText($list, 'replyTo', 'MAIL_LOG_ENTITY.LABEL.REPLY_TO');
        $this->configureListFieldText($list, 'to', 'MAIL_LOG_ENTITY.LABEL.TO');
        $this->configureListFieldText($list, 'subject', 'MAIL_LOG_ENTITY.LABEL.SUBJECT');
        $this->configureListFieldHtml($list, 'message', 'MAIL_LOG_ENTITY.LABEL.MESSAGE');
        $this->configureListFieldCreatedAt($list);
        $this->configureListFieldActions($list, ['show' => []]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('status', null, ['label' => 'MAIL_LOG_ENTITY.LABEL.STATUS'])
            ->add('subject', null, ['label' => 'MAIL_LOG_ENTITY.LABEL.SUBJECT'])
            ->add(
                'message',
                null,
                [
                    'label'    => 'MAIL_LOG_ENTITY.LABEL.MESSAGE',
                    'template' => 'admin/CRUD/mail_log/show__message.html.twig',
                ]
            )
            ->add('from', null, ['label' => 'MAIL_LOG_ENTITY.LABEL.FROM'])
            ->add('replyTo', 'email', ['label' => 'MAIL_LOG_ENTITY.LABEL.REPLY_TO'])
            ->add('to', null, ['label' => 'MAIL_LOG_ENTITY.LABEL.TO'])
            ->add('cc', null, ['label' => 'MAIL_LOG_ENTITY.LABEL.CC'])
            ->add('bcc', null, ['label' => 'MAIL_LOG_ENTITY.LABEL.BCC'])
            ->add('debug', null, ['label' => 'MAIL_LOG_ENTITY.LABEL.DEBUG'])
            ->add('createdAt', 'datetime', ['label' => 'FIELD.CREATED_AT']);
    }
}
