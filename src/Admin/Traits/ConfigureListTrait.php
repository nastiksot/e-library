<?php

declare(strict_types=1);

namespace App\Admin\Traits;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use function array_merge;

trait ConfigureListTrait
{
    protected function configureListFieldDate(
        ListMapper $list,
        string $name,
        ?string $label = null,
        array $options = [],
    ): void {
        $list->add(
            $name,
            FieldDescriptionInterface::TYPE_DATETIME,
            array_merge(
                [
                    'label'        => $label,
                    'header_style' => 'width:130px;',
                ],
                $options
            )
        );
    }

    protected function configureListFieldCreatedAt(ListMapper $list): void
    {
        $this->configureListFieldDate($list, 'createdAt', 'FIELD.CREATED_AT');
    }

    protected function configureListFieldUpdatedAt(ListMapper $list): void
    {
        $this->configureListFieldDate($list, 'updatedAt', 'FIELD.UPDATED_AT');
    }

    protected function configureListFieldActive(
        ListMapper $list,
        ?string $name = null,
        ?string $label = null,
        array $options = []
    ): void {
        $name  = $name ?? 'active';
        $label = $label ?? 'FIELD.ACTIVE';
        $list->add(
            $name,
            FieldDescriptionInterface::TYPE_BOOLEAN,
            array_merge(
                [
                    'label'        => $label,
                    'header_style' => 'width:100px;',
                    'editable'     => true,
                ],
                $options
            )
        );
    }

    protected function configureListFieldText(
        ListMapper $list,
        string $name,
        ?string $label = null,
        array $options = []
    ): void {
        $list->add(
            $name,
            'text',
            array_merge(
                [
                    'label' => $label,
                ],
                $options
            )
        );
    }

    protected function configureListFieldHtml(
        ListMapper $list,
        string $name,
        ?string $label = null,
        array $options = []
    ): void {
        $list->add(
            $name,
            'html',
            array_merge(
                [
                    'label'    => $label,
                    'truncate' => ['length' => 300, 'preserve' => true],
                ],
                $options
            )
        );
    }

    protected function configureListFieldPosition(
        ListMapper $list,
        ?string $name = null,
        ?string $label = null,
        array $options = []
    ): void {
        $name  = $name ?? 'position';
        $label = $label ?? 'FIELD.POSITION';
        $list->add(
            $name,
            FieldDescriptionInterface::TYPE_INTEGER,
            array_merge(
                [
                    'label'        => $label,
                    'header_style' => 'width:100px;',
                    'editable'     => true,
                ],
                $options
            )
        );
    }

    protected function configureListFieldSort(ListMapper $list): void
    {
        $list
            ->add(
                '_move_action',
                'actions',
                [
                    'header_style' => 'width:155px;',
                    'actions'      => [
                        'move' => ['template' => '@PixSortableBehavior/Default/_sort.html.twig'],
                    ],
                ]
            );
    }

    protected function configureListFieldLinkEntity(
        ListMapper $list,
        string $name,
        ?string $label = null,
        array $options = []
    ): void {
        $list
            ->add(
                $name,
                'url',
                array_merge(
                    [
                        'label'      => $label ?? 'FIELD.LINK_ENTITY.LINK',
                        'attributes' => ['target' => '_blank'],
                        'template'   => 'admin/CRUD/list__url_link_entity.html.twig',
                    ],
                    $options
                )
            );
    }

    protected function configureListFieldActions(ListMapper $list, array $actions = [], array $options = []): void
    {
        $actions = $actions ?: ['edit' => [], 'delete' => []];
        $list->add(
            ListMapper::NAME_ACTIONS,
            ListMapper::TYPE_ACTIONS,
            array_merge(
                [
                    'actions'      => $actions,
                    'label'        => 'td_action',
                    'header_style' => 'min-width:250px;',
                ],
                $options,
            )
        );
    }

    protected function configureListFieldChildrenActions(
        ListMapper $list,
        array $actions = [],
        array $options = []
    ): void {
        $actions = $actions ?: [
            'children' => ['template' => 'admin/CRUD/list__action_children.html.twig'],
            'edit'     => [],
            'delete'   => [],
        ];

        $this->configureListFieldActions($list, $actions, $options);
    }

    protected function configureListFieldMoveAction(
        ListMapper $list,
        ?string $name = null,
        ?string $label = null,
        array $options = []
    ): void {
        $list
            ->add(
                $name ?? '_move_action',
                'actions',
                array_merge(
                    [
                        'label'        => $label ?? 'FIELD.MOVE',
                        'header_style' => $options['header_style'] ?? 'min-width:160px',
                        'actions'      => [
                            'move' => ['template' => $options['template'] ?? '@PixSortableBehavior/Default/_sort.html.twig'],
                        ],
                    ],
                    $options
                )
            );
    }

    protected function configureListFieldImageEntity(
        ListMapper $list,
        string $name,
        ?string $label = null,
        array $options = []
    ): void {
        $this->configureListFieldText(
            $list,
            $name,
            $label ?? 'FIELD.IMAGE.LABEL',
            array_merge(
                [
                    'header_style' => 'width: 220px',
                    'template'     => 'admin/CRUD/list__image_entity.html.twig',
                    'sortable'     => false,
                ],
                $options
            )
        );
    }

    protected function configureListFieldFileOriginalLinkEntity(
        ListMapper $list,
        string $name,
        string $label,
        array $options = []
    ): void {
        $this->configureListFieldText(
            $list,
            $name,
            $label,
            array_merge(
                [
                    'header_style' => 'width: 220px',
                    'template'     => 'admin/CRUD/list__file_original_link_entity.html.twig',
                    'propertyName' => $name,
                    'sortable'     => false,
                ],
                $options
            )
        );
    }
}
