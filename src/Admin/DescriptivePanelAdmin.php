<?php

declare(strict_types=1);

namespace App\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Contracts\Dictionary\DescriptivePanelPage;
use App\Service\LocaleProvider\LocaleProvider;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DescriptivePanelAdmin extends AbstractFileMimeTypeAdmin
{
    use ConfigureAdminFullTrait;

    protected LocaleProvider $localeProvider;

    #[Required]
    public function initLocaleProvider(LocaleProvider $localeProvider, ): void
    {
        $this->localeProvider = $localeProvider;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);

        $collection->remove('delete');
        $collection->remove('create');
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues = [
            '_sort_order' => 'ASC',
            '_sort_by'    => 'position',
        ];
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('pages', null, ['label' => 'DESCRIPTIVE_PANEL_ENTITY.LABEL.PAGES'])
            ->add('translations.title', null, ['label' => 'DESCRIPTIVE_PANEL_ENTITY.LABEL.TITLE']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldPosition($list);

        $this->configureListFieldFileOriginalLinkEntity(
            $list,
            'fileName',
            'DESCRIPTIVE_PANEL_ENTITY.LABEL.FILE',
            ['target' => '_blank']
        );

        $this->configureListFieldText($list, 'translate.title', 'DESCRIPTIVE_PANEL_ENTITY.LABEL.TITLE');
        $this->configureListFieldText(
            $list,
            'pages',
            'DESCRIPTIVE_PANEL_ENTITY.LABEL.PAGES',
            ['template' => 'admin/CRUD/list__descriptive_panel_page_entity.html.twig']
        );
        $this->configureListFieldActive($list);
        $this->configureListFieldActions($list);
    }

    protected function configureFormFieldPages(FormMapper $form): void
    {
        $choices = [];

        foreach (DescriptivePanelPage::toArray() as $key => $value) {
            $choices[$value] = $value;
        }

        $this->configureFormFieldChoice(
            $form,
            'pages',
            $choices,
            'DESCRIPTIVE_PANEL_ENTITY.LABEL.PAGES',
            'DESCRIPTIVE_PANEL_ENTITY.HELP.PAGES',
            true,
            false,
            true
        );
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('DESCRIPTIVE_PANEL_ENTITY.SECTION.MAIN');

        $this->configureFormFieldActive($form, 'DESCRIPTIVE_PANEL_ENTITY.LABEL.ACTIVE', 'DESCRIPTIVE_PANEL_ENTITY.HELP.ACTIVE');
        $this->configureFormFieldPages($form);

        $form
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'          => 'DESCRIPTIVE_PANEL_ENTITY.LABEL.TRANSLATIONS',
                    'default_locale' => $this->parameterBag->get('defaultLocale'),
                    'locale_labels'  => $this->localeProvider->getLocaleTitlesForAdmin(),
                    'fields'         => [
                        'title' => [
                            'field_type'         => TextType::class,
                            'label'              => 'DESCRIPTIVE_PANEL_ENTITY.LABEL.TITLE',
                            'help'               => 'DESCRIPTIVE_PANEL_ENTITY.HELP.TITLE',
                            'required'           => true,
                            'translation_domain' => $this->getTranslationDomain(),
                            'constraints'        => [
                                new NotBlank(),
                            ],
                        ],
                        'description' => [
                            'field_type'         => TextareaType::class,
                            'label'              => 'DESCRIPTIVE_PANEL_ENTITY.LABEL.DESCRIPTION',
                            'help'               => 'DESCRIPTIVE_PANEL_ENTITY.HELP.DESCRIPTION',
                            'required'           => true,
                            'translation_domain' => $this->getTranslationDomain(),
                            'constraints'        => [
                                new NotBlank(),
                            ],
                        ],
                        'file' => [ /// ????????? ERROR: Field(s) 'file' doesn't exist in App\Entity\DescriptivePanelTranslation
                            'field_type'         => VichImageType::class,
                            'label'              => 'DESCRIPTIVE_PANEL_ENTITY.LABEL.FILE',
                            'help'               => 'DESCRIPTIVE_PANEL_ENTITY.HELP.FILE',
                            'required'           => false,
                            'allow_delete'       => true,
                            'download_uri'       => true,
                            'image_uri'          => true,
                            'translation_domain' => $this->getTranslationDomain(),
                        ],
                    ],
                    'excluded_fields' => ['fileMimeType', 'createdAt', 'updatedAt'],
                ]
            );

        $form->end();
    }
}
