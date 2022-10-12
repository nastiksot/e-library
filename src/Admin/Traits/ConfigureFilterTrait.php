<?php

declare(strict_types=1);

namespace App\Admin\Traits;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use function array_merge;

trait ConfigureFilterTrait
{
    protected function configureFilterFieldDate(
        DatagridMapper $filter,
        string $name,
        string $label = null,
        array $options = []
    ): void {
        $options['label'] = $label;

        $filter->add(
            $name,
            DateFilter::class,
            $options,
            [
                'datepicker_use_button' => false,
                //'view_timezone'         => $this->getTimezone(), // search considering timezone
            ]
        );
    }

    protected function configureFilterFieldDateRange(
        DatagridMapper $filter,
        string $name,
        string $label = null,
        array $options = [],
        array $fieldOptions = [],
    ): void {
        $options['label'] = $label;
        $filter
            ->add(
                $name,
                DateRangeFilter::class,
                array_merge(
                    [
                        'field_options' => array_merge(
                            [
                                'field_options' => ['widget' => 'single_text'],
                            ],
                            $fieldOptions
                        ),
                    ],
                    $options
                )
            );
    }

    protected function configureFilterFieldCreatedAt(DatagridMapper $filter): void
    {
        $this->configureFilterFieldDate($filter, 'createdAt', 'FIELD.CREATED_AT');
    }

    protected function configureFilterFieldCreatedAtDateRange(DatagridMapper $filter): void
    {
        $this->configureFilterFieldDateRange($filter, 'createdAt', 'FIELD.CREATED_AT');
    }

    protected function configureFilterFieldUpdatedAt(DatagridMapper $filter): void
    {
        $this->configureFilterFieldDate($filter, 'updatedAt', 'FIELD.UPDATED_AT');
    }

    protected function configureFilterFieldUpdatedAtDateRange(DatagridMapper $filter): void
    {
        $this->configureFilterFieldDateRange($filter, 'updatedAt', 'FIELD.UPDATED_AT');
    }

    protected function configureFilterFieldText(
        DatagridMapper $filter,
        string $name,
        string $label = null,
        array $options = []
    ): void {
        $options['label'] = $label;
        $filter->add($name, null, $options);
    }

    protected function configureFilterFieldActive(
        DatagridMapper $filter,
        ?string $name = null,
        ?string $label = null,
        array $options = []
    ): void {
        $name             = $name ?? 'active';
        $label            = $label ?? 'FIELD.ACTIVE';
        $options['label'] = $label;
        $filter->add($name, null, $options);
    }

    protected function configureFilterFieldChoice(
        DatagridMapper $filter,
        string $name,
        array $choices = [],
        ?string $label = null,
        array $options = []
    ): void {
        $filter->add(
            $name,
            ChoiceFilter::class,
            [
                'label'         => $label ?? 'FIELD.CHOICE',
                'global_search' => true,
                'field_type'    => ChoiceType::class,
                'field_options' => [
                    'choices'            => $choices,
                    'translation_domain' => $this->getTranslationDomain(),
                ],
            ]
        );
    }
}
