<?php

declare(strict_types=1);

namespace App\Admin\User\Profile;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Admin\Traits\OrderStatusChoicesTrait;
use App\Admin\Traits\ReadingTypeChoicesTrait;
use App\Entity\Reading;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Reading|null getSubject()
 */
class ReadingUserProfileAdmin extends AbstractUserProfileAdmin
{
    use ConfigureAdminFullTrait;
    use OrderStatusChoicesTrait;
    use ReadingTypeChoicesTrait;

    protected function getAccessMapping(): array
    {
        return [
            'prolong'        => 'PROLONG',
            'prolong_cancel' => 'PROLONG_CANCEL',
        ];
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->add('prolong', $this->getRouterIdParameter() . '/prolong');
        $collection->add('prolong_cancel', $this->getRouterIdParameter() . '/prolong-cancel');
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

        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
        $this->configureFilterFieldDateRange($filter, 'startAt', 'READING_ENTITY.LABEL.START_AT');
        $this->configureFilterFieldDateRange($filter, 'endAt', 'READING_ENTITY.LABEL.END_AT');
        $this->configureFilterFieldDateRange($filter, 'prolongAt', 'READING_ENTITY.LABEL.PROLONG_AT');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'order.id', 'READING_ENTITY.LABEL.ORDER_ID');
        $this->configureListFieldText($list, 'readingType', 'READING_ENTITY.LABEL.READING_TYPE');
        $this->configureListFieldText($list, 'book', 'READING_ENTITY.LABEL.BOOK');
        $this->configureListFieldText($list, 'quantity', 'READING_ENTITY.LABEL.QUANTITY');
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
            'prolong'        => ['template' => 'admin/user/profile/list__action_prolong.html.twig'],
            'prolong_cancel' => ['template' => 'admin/user/profile/list__action_prolong_cancel.html.twig'],
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
