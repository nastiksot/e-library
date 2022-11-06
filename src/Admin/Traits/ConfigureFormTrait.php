<?php

declare(strict_types=1);

namespace App\Admin\Traits;

use App\Contracts\Entity\ItemsListEntityInterface;
use App\Contracts\Entity\LinkEntityInterface;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

use function array_merge;
use function implode;
use function is_array;

use const PHP_EOL;

trait ConfigureFormTrait
{
    protected static array $choicesYesNo = [
        'CHOICE.YES' => true,
        'CHOICE.NO'  => false,
    ];

    protected function configureFormFieldChoice(
        FormMapper $form,
        string $name,
        array $choices = [],
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        bool $expanded = false,
        bool $multiple = false
    ): void {
        $form->add(
            $name,
            ChoiceType::class,
            [
                'label'              => $label ?? 'FIELD.CHOICE',
                'help'               => $help ?? '',
                'choices'            => $choices,
                'translation_domain' => $this->getTranslationDomain(),
                'required'           => $required,
                'expanded'           => $expanded,
                'multiple'           => $multiple,
            ]
        );
    }

    protected function configureFormFieldYesNo(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = true
    ): void {
        $label = $label ?? 'FIELD.YES_NO';
        $this->configureFormFieldChoice($form, $name, self::$choicesYesNo, $label, $help, $required);
    }

    protected function configureFormFieldActive(
        FormMapper $form,
        ?string $label = null,
        ?string $help = null
    ): void {
        $label = $label ?? 'FIELD.ACTIVE';
        $this->configureFormFieldYesNo($form, 'active', $label, $help);
    }

    protected function configureFormFieldText(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = []
    ): void {
        $form->add(
            $name,
            TextType::class,
            array_merge(
                [
                    'required'           => $required,
                    'label'              => $label,
                    'help'               => $help,
                    'translation_domain' => $this->getTranslationDomain(),
                ],
                $options
            )
        );
    }

    protected function configureFormFieldNumber(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = []
    ): void {
        $form->add(
            $name,
            NumberType::class,
            array_merge(
                [
                    'required'           => $required,
                    'label'              => $label,
                    'help'               => $help,
                    'translation_domain' => $this->getTranslationDomain(),
                ],
                $options
            )
        );
    }

    protected function configureFormFieldPosition(
        FormMapper $form,
        ?string $name = null,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = []
    ): void {
        $this->configureFormFieldNumber(
            $form,
            $name ?? 'position',
            $label ?? 'FIELD.POSITION',
            $help,
            $required,
            $options
        );
    }

    protected function configureFormFieldTextarea(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        int $rows = 10
    ): void {
        $options = [
            'label'              => $label,
            'help'               => $help,
            'required'           => $required,
            'attr'               => ['rows' => $rows],
            'translation_domain' => $this->getTranslationDomain(),
        ];
        $form->add($name, TextareaType::class, $options);
    }

    protected function configureFormFieldDate(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = []
    ): void {
        $form->add(
            $name,
            DatePickerType::class,
            array_merge(
                [
                    'required'           => $required,
                    'label'              => $label,
                    'help'               => $help,
                    'translation_domain' => $this->getTranslationDomain(),
                ],
                $options
            )
        );
    }

    protected function configureFormFieldDateType(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = []
    ): void {
        $form->add(
            $name,
            DateType::class,
            array_merge(
                [
                    'widget'             => 'single_text',
                    'attr'               => ['class' => 'w-150'],
                    'required'           => $required,
                    'label'              => $label,
                    'help'               => $help,
                    'translation_domain' => $this->getTranslationDomain(),
                ],
                $options
            )
        );

//        $form->add(
//            'startAt',
//            DateType::class,
//            [
//                'required'    => false,
//                'widget'      => 'single_text',
//                'constraints' => [
//                    new NotBlank(),
//                    new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
//                ],
//            ]
//        );
    }

    protected function configureFormFieldReplacements(
        FormMapper $form,
        ?string $name = null,
        $data = null,
        ?string $label = null,
        ?string $help = null,
        ?int $rows = null
    ): void {
        $name  = $name ?? 'replacements';
        $label = $label ?? 'FIELD_REPLACEMENTS.LABEL';
        $help  = $help ?? 'FIELD_REPLACEMENTS.HELP';
        $data  = is_array($data) ? implode(' ' . PHP_EOL, $data) : null;
        $data  = $data ?? '';
        $rows  = $rows ?? 7;

        $form
            ->add(
                $name,
                TextareaType::class,
                [
                    'label'              => $label,
                    'help'               => $help,
                    'required'           => false,
                    'attr'               => ['rows' => $rows, 'readonly' => 'readonly'],
                    'data'               => $data,
                    'mapped'             => false,
                    'translation_domain' => $this->getTranslationDomain(),
                    // 'disabled' => true,
                ]
            );
    }

    protected function configureFormFieldCKEditor(
        FormMapper $form,
        string $name,
        ?string $label = null,
        bool $required = false,
        string $configName = self::FORM_FIELD_CONTENT_CONFIG_ADVANCED,
        array $constraints = [],
        array $config = []
    ): void {
        $label = $label ?? 'FIELD_CONTENT.LABEL';

        $form
            ->add(
                $name,
                CKEditorType::class,
                [
                    'label'       => $label,
                    'required'    => $required,
                    'config_name' => $configName,
                    'trim'        => true,
                    'constraints' => array_merge($required ? [new NotBlank()] : [], $constraints),
                    'config'      => $config,
                ]
            );
    }

    protected function configureFormFieldElFinder(
        FormMapper $form,
        string $name,
        ?string $label = null,
        bool $required = false
    ): void {
        $form->add(
            $name,
            ElFinderType::class,
            [
                'label'    => $label,
                'required' => $required,
                'instance' => 'form',
                'attr'     => ['class' => 'form-control'],
                'enable'   => true,
            ]
        );
    }

    protected function configureFormFieldVichImage(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = []
    ): void {
        $label = $label ?? 'FIELD.IMAGE.LABEL';
        $help  = $help ?? 'FIELD.IMAGE.HELP';

        // add image file
        $form
            ->add(
                $name,
                VichImageType::class,
                array_merge(
                    [
                        'label'        => $label ? $this->trans($label, [], $this->getTranslationDomain()) : null,
                        'required'     => $required,
                        'allow_delete' => true,
                        'download_uri' => true,
                        'image_uri'    => true,
                        'help'         => $help ? $this->trans($help, [], $this->getTranslationDomain()) : null,
                    ],
                    $options
                )
            );
    }

    protected function configureFormFieldLinkEntity(
        FormMapper $form,
        bool $uselinkTitle = false,
        string $fieldsPrefix = null,
        array $labels = []
    ): void {
        // fields names
        $nameLink       = $fieldsPrefix ? $fieldsPrefix . 'Link' : 'link';
        $namelinkType   = $fieldsPrefix ? $fieldsPrefix . 'LinkType' : 'linkType';
        $nameLinkTitle  = $fieldsPrefix ? $fieldsPrefix . 'LinkTitle' : 'linkTitle';
        $nameLinkTarget = $fieldsPrefix ? $fieldsPrefix . 'LinkTarget' : 'linkTarget';
        $nameLinkPage   = $fieldsPrefix ? $fieldsPrefix . 'LinkPage' : 'linkPage';
        $nameNoFollow   = $fieldsPrefix ? $fieldsPrefix . 'NoFollow' : 'noFollow';

        // fields maps
        $mapExternal = [$nameLink, $nameLinkTitle, $nameNoFollow, $nameLinkTarget];
        $mapPage     = [$nameLinkPage, $nameLinkTitle, $nameNoFollow, $nameLinkTarget];

        $form
            ->add(
                $namelinkType,
                ChoiceFieldMaskType::class,
                [
                    'label'              => $labels['linkType'] ?? 'FIELD.LINK_ENTITY.TYPE',
                    'required'           => false,
                    'choices'            => [
                        'FIELD.LINK_ENTITY.CHOICE.LINK' => LinkEntityInterface::LINK_TYPE_EXTERNAL_LINK,
                        'FIELD.LINK_ENTITY.CHOICE.PAGE' => LinkEntityInterface::LINK_TYPE_PAGE,
                    ],
                    'map'                => [
                        LinkEntityInterface::LINK_TYPE_EXTERNAL_LINK => $mapExternal,
                        LinkEntityInterface::LINK_TYPE_PAGE          => $mapPage,
                    ],
                    'placeholder'        => 'FIELD.LINK_ENTITY.CHOICE.NONE',
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            );

        $form
            ->add(
                $nameLinkPage,
                ModelType::class,
                [
                    'label'    => $labels['linkPage'] ?? 'FIELD.LINK_ENTITY.PAGE',
                    'help'     => $labels['linkPageHelp'] ?? 'FIELD.LINK_ENTITY.PAGE_HELP',
                    'required' => false,
                    'btn_add'  => false,
                ],
                ['admin_code' => 'admin.page']
            );

        $form
            ->add(
                $nameLink,
                TextType::class,
                [
                    'label'              => $labels['link'] ?? 'FIELD.LINK_ENTITY.LINK',
                    'help'               => $labels['linkHelp'] ?? 'FIELD.LINK_ENTITY.LINK_HELP',
                    'required'           => false,
                    // 'attr'     => ['class' => 'link_input_custom'],
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            );

        if ($uselinkTitle) {
            $form
                ->add(
                    $nameLinkTitle,
                    TextType::class,
                    [
                        'label'              => $labels['linkTitle'] ?? 'FIELD.LINK_ENTITY.LINK_TITLE',
                        'help'               => null,
                        'required'           => false,
                        // 'attr'     => ['class' => 'link_input_custom'],
                        'translation_domain' => $this->getTranslationDomain(),
                    ]
                );
        }

        $form
            ->add(
                $nameNoFollow,
                ChoiceType::class,
                [
                    'label'              => $labels['noFollow'] ?? 'FIELD.LINK_ENTITY.NO_FOLLOW',
                    'choices'            => self::$choicesYesNo,
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            );

        $form
            ->add(
                $nameLinkTarget,
                ChoiceType::class,
                [
                    'label'              => $labels['linkTarget'] ?? 'FIELD.LINK_ENTITY.TARGET',
                    'choices'            => [
                        'FIELD.LINK_ENTITY.CHOICE.TOP'   => LinkEntityInterface::TARGET_TOP,
                        'FIELD.LINK_ENTITY.CHOICE.BLANK' => LinkEntityInterface::TARGET_BLANK,
                    ],
                    'translation_domain' => $this->getTranslationDomain(),
                    // 'attr'               => ['class' => 'link_input_custom'],
                ]
            );
    }

    protected function configureFormFieldItemsListEntity(
        FormMapper $form,
        bool $useRand = false,
        bool $usePaging = false,
        bool $disabled = false
    ) {
        // default choices
        $choices = [
            'FIELD.ITEMS_LIST_ENTITY.CHOICE.ALL'  => ItemsListEntityInterface::LIST_TYPE_ALL,
            'FIELD.ITEMS_LIST_ENTITY.CHOICE.LAST' => ItemsListEntityInterface::LIST_TYPE_LAST,
        ];

        // default map
        $map = [
            ItemsListEntityInterface::LIST_TYPE_ALL  => [],
            ItemsListEntityInterface::LIST_TYPE_LAST => ['listLimit'],
        ];

        // add rand
        if (true === $useRand) {
            $choices['FIELD.ITEMS_LIST_ENTITY.CHOICE.RAND'] = ItemsListEntityInterface::LIST_TYPE_RAND;
            $map[ItemsListEntityInterface::LIST_TYPE_RAND]  = ['listLimit'];
        }

        // add paging
        if (true === $usePaging) {
            $choices['FIELD.ITEMS_LIST_ENTITY.CHOICE.PAGINATED'] = ItemsListEntityInterface::LIST_TYPE_PAGINATED;
            $map[ItemsListEntityInterface::LIST_TYPE_PAGINATED]  = ['listLimit'];
        }

        // build fields
        $form
            ->add(
                'listType',
                ChoiceFieldMaskType::class,
                [
                    'label'              => 'FIELD.ITEMS_LIST_ENTITY.TYPE',
                    'choices'            => $choices,
                    'map'                => $map,
                    'required'           => false,
                    'translation_domain' => $this->getTranslationDomain(),
                    'disabled'           => $disabled,
                ]
            )
            ->add(
                'listLimit',
                IntegerType::class,
                [
                    'label'              => 'FIELD.ITEMS_LIST_ENTITY.LIMIT',
                    'required'           => false,
                    'translation_domain' => $this->getTranslationDomain(),
                    'disabled'           => $disabled,
                ]
            );
    }

    protected function configureFormFieldTime(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false
    ): void {
        $options = [
            'label'              => $label,
            'help'               => $help,
            'required'           => $required,
            'attr'               => ['style' => 'width: 220px;'],
            'translation_domain' => $this->getTranslationDomain(),
        ];
        $form->add($name, null, $options);
    }

    protected function configureFormFieldYoutubeVideoId(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = []
    ): void {
        $form->add(
            $name,
            TextType::class,
            array_merge(
                [
                    'required'           => $required,
                    'label'              => $label ?? 'FIELD.YOUTUBE_VIDEO_ID',
                    'help'               => $help ?? 'FIELD.YOUTUBE_VIDEO_ID_HELP',
                    'translation_domain' => $this->getTranslationDomain(),
                ],
                $options
            )
        );
    }

    protected function configureFormFieldVichCustomFile(
        FormMapper $form,
        string $name,
        ?string $label = null,
        ?string $help = null,
        bool $required = false,
        array $options = [],
    ): void {
        $form
            ->add(
                $name,
                VichImageType::class,
                array_merge(
                    [
                        'label'        => $label ? $this->trans($label, [], $this->getTranslationDomain()) : null,
                        'required'     => $required,
                        'allow_delete' => true,
                        'download_uri' => true,
                        'image_uri'    => false,
                        'help'         => $help ? $this->trans($help, [], $this->getTranslationDomain()) : null,
                    ],
                    $options
                )
            );
    }
}
