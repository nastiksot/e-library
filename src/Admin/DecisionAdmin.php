<?php

declare(strict_types=1);

namespace App\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Contracts\Dictionary\DecisionAction;
use App\Entity\Decision;
use App\Repository\DecisionRepository;
use App\Service\LocaleProvider\LocaleProvider;
use JetBrains\PhpStorm\ArrayShape;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @method Decision|null getSubject()
 */
class DecisionAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected DecisionRepository $decisionRepository;

    protected LocaleProvider $localeProvider;

    #[Required]
    public function initLocaleProvider(LocaleProvider $localeProvider, ): void
    {
        $this->localeProvider = $localeProvider;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues = [
            '_sort_order' => 'ASC',
            '_sort_by'    => 'position',
        ];
    }

    #[Required]
    public function setRepositories(
        DecisionRepository $decisionRepository,
    ): void {
        $this->decisionRepository = $decisionRepository;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->add('show');
    }

    protected function configure(): void
    {
        $this->getTemplateRegistry()->setTemplate('edit', 'admin/CRUD/decision/edit.html.twig');
        //$this->getTemplateRegistry()->setTemplate('show', 'admin/CRUD/decision/show.html.twig');
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        $query = parent::configureQuery($query);

        if (false === $this->isChild()
        ) {
            $alias = $query->getRootAliases()[0];
            $query->andWhere($alias . '.parentDecision IS NULL');
        }

        return $query;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('adminTitle', null, ['label' => 'DECISION_ENTITY.LABEL.ADMIN_TITLE']);
        $filter->add('translations.question', null, ['label' => 'DECISION_ENTITY.LABEL.QUESTION']);
        $filter->add('translations.answer', null, ['label' => 'DECISION_ENTITY.LABEL.ANSWER']);
        $this->configureFilterFieldActive($filter);
        $this->configureFilterFieldActive($filter, 'final', 'DECISION_ENTITY.LABEL.FINAL');
        $this->configureFilterFieldActive($filter, 'positive', 'DECISION_ENTITY.LABEL.POSITIVE');
        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldPosition($list);

        // is top level
        if (false === $this->isChild()) {
            $this->configureListFieldText(
                $list,
                'adminTitle',
                'DECISION_ENTITY.LABEL.ADMIN_TITLE',
                ['identifier' => true, 'route' => ['name' => 'edit']]
            );
        } else {
            $this->configureListFieldText(
                $list,
                'answer',
                'DECISION_ENTITY.LABEL.ANSWER',
                ['identifier' => true, 'route' => ['name' => 'edit']]
            );
            $this->configureListFieldText(
                $list,
                'question',
                'DECISION_ENTITY.LABEL.QUESTION',
                ['identifier' => true, 'route' => ['name' => 'edit']]
            );
        }

        $this->configureListFieldActive($list);

        // list actions with child menu
        $actions = !$this->isChild()
            ? [
                'show'     => [],
                'children' => ['template' => 'admin/CRUD/decision/list__action_children_parent.html.twig'],
                'edit'     => [],
                'delete'   => [],
            ]
            : [
                'children' => ['template' => 'admin/CRUD/decision/list__action_children_parent.html.twig'],
                'edit'     => [],
                'delete'   => [],
            ];

        $options = ['header_style' => 'min-width:350px;'];

        $this->configureListFieldChildrenActions($list, $actions, $options);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        // is top level
        if (false === $this->isChild()) {
            $this->configureFormFieldText(
                $form,
                'adminTitle',
                'DECISION_ENTITY.LABEL.ADMIN_TITLE',
                'DECISION_ENTITY.HELP.ADMIN_TITLE',
                true
            );

            $form
                ->add(
                    'product',
                    ModelType::class,
                    [
                        'property'    => 'adminLabel',
                        'label'       => 'DECISION_ENTITY.LABEL.PRODUCT',
                        'help'        => 'DECISION_ENTITY.HELP.PRODUCT',
                        'btn_add'     => false,
                        'required'    => false,
                        'constraints' => [new NotBlank()],
                    ],
                    ['admin_code' => 'admin.product']
                );
        }

        $this->configureFormSectionTranslations($form);

        $form
            ->add(
                'final',
                ChoiceFieldMaskType::class,
                [
                    'label'   => 'DECISION_ENTITY.LABEL.FINAL',
                    'help'    => 'DECISION_ENTITY.HELP.FINAL',
                    'choices' => self::$choicesYesNo,
                    'map'     => [
                        1 => ['positive'],
                    ],
                    'required'           => true,
                    'translation_domain' => $this->getTranslationDomain(),
                    'attr'               => ['class' => 'decision-final'],
                ]
            )
            ->add(
                'positive',
                ChoiceFieldMaskType::class,
                [
                    'label'   => 'DECISION_ENTITY.LABEL.POSITIVE',
                    'help'    => 'DECISION_ENTITY.HELP.POSITIVE',
                    'choices' => self::$choicesYesNo,
                    'map'     => [
                        1 => ['action'],
                    ],
                    'required'           => true,
                    'translation_domain' => $this->getTranslationDomain(),
                    'attr'               => ['class' => 'decision-positive'],
                ]
            )
            ->add(
                'action',
                ChoiceFieldMaskType::class,
                [
                    'label'   => 'DECISION_ENTITY.LABEL.ACTION',
                    'help'    => 'DECISION_ENTITY.HELP.ACTION',
                    'choices' => $this->getActionProductsChoices(),
                    'map'     => [
                        DecisionAction::REPLACE_MAIN()->getValue() => ['products'],
                    ],
                    'required'           => false,
                    'translation_domain' => $this->getTranslationDomain(),
                    'attr'               => ['class' => 'decision-action'],
                ]
            )
            ->add(
                'products',
                ModelType::class,
                [
                    'property'     => 'adminLabel',
                    'multiple'     => true,
                    'by_reference' => false,
                    'label'        => 'DECISION_ENTITY.LABEL.PRODUCTS',
                    'help'         => 'DECISION_ENTITY.HELP.PRODUCTS',
                    'required'     => false,
                    'btn_add'      => false,
                    'attr'         => ['class' => 'decision-products'],
                ]
            );
    }

    private function configureFormSectionTranslations(FormMapper $form): void
    {
        $excludedFields = ['createdAt', 'updatedAt'];

        // is top level
        if (false === $this->isChild()) {
            // modify translated fields for top level
            $excludedFields[]   = 'answer';
            $translationsFields = [
                'question' => [
                    'field_type'         => TextType::class,
                    'label'              => 'DECISION_ENTITY.LABEL.QUESTION',
                    'help'               => 'DECISION_ENTITY.HELP.QUESTION',
                    'required'           => true,
                    'translation_domain' => $this->getTranslationDomain(),
                    'constraints'        => [new NotBlank()],
                ],
            ];
        } else {
            // modify translated fields for sub level
            $translationsFields = [
                'answer' => [
                    'field_type'         => TextType::class,
                    'label'              => 'DECISION_ENTITY.LABEL.ANSWER',
                    'help'               => 'DECISION_ENTITY.HELP.ANSWER',
                    'required'           => true,
                    'translation_domain' => $this->getTranslationDomain(),
                    'constraints'        => [new NotBlank()],
                ],
                'question' => [
                    'field_type'         => TextType::class,
                    'label'              => 'DECISION_ENTITY.LABEL.QUESTION',
                    'help'               => 'DECISION_ENTITY.HELP.QUESTION',
                    'required'           => false,
                    'translation_domain' => $this->getTranslationDomain(),
                ],
            ];
        }

        $form
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'           => 'FIELD.TRANSLATIONS',
                    'default_locale'  => $this->parameterBag->get('defaultLocale'),
                    'locale_labels'   => $this->localeProvider->getLocaleTitlesForAdmin(),
                    'fields'          => $translationsFields,
                    'excluded_fields' => $excludedFields,
                ]
            );
    }

    private function getActionProductsChoices(): array
    {
        $choices = [];

        foreach (DecisionAction::toArray() as $key => $value) {
            $choices['DECISION_ENTITY.CHOICES.ACTION.' . $key] = $value;
        }

        return $choices;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        // general entity fields
        $show->add('id')
            ->add('question', null, ['label' => 'DECISION_ENTITY.LABEL.QUESTION'])
            ->add('createdAt', 'datetime', ['label' => 'FIELD.CREATED_AT'])
            ->add('updatedAt', 'datetime', ['label' => 'FIELD.UPDATED_AT']);

        // tree
        $show
            ->add(
                'position',
                null,
                [
                    'label'    => 'DECISION_ENTITY.LABEL.TREE',
                    'template' => 'admin/CRUD/decision/show__action_tree.html.twig',
                    'data'     => $this->resolveShowTemplateData($this->getSubject()->getId()),
                ]
            );
    }

    #[ArrayShape(['decisionsTree' => 'array'])]
    private function resolveShowTemplateData(
        int $parentDecisionId
    ): array {
        return [
            'decisionsTree' => $this->resolveDecisionTree($parentDecisionId),
        ];
    }

    private function resolveDecisionTree(int $parentDecisionId): array
    {
        $tree      = [];
        $decisions = $this->decisionRepository->getDecisionsByParentDecisionId($parentDecisionId);

        foreach ($decisions as $decision) {
            $tree[] = [
                'decision' => $decision,
                'sub'      => $this->resolveDecisionTree($decision->getId()),
            ];
        }

        return $tree;
    }
}
