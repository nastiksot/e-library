<?php

declare(strict_types=1);

namespace App\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Partner;
use App\Service\LocaleProvider\LocaleProvider;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @method Partner|null getSubject()
 */
class PartnerAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

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

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('translations.title', null, ['label' => 'PARTNER_ENTITY.LABEL.TITLE']);
        $this->configureFilterFieldActive($filter);
        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldPosition($list);
        $this->configureListFieldImageEntity($list, 'image', 'PARTNER_ENTITY.LABEL.IMAGE');
        $this->configureListFieldText($list, 'translate.title', 'PARTNER_ENTITY.LABEL.TITLE');
        $this->configureListFieldActive($list);
        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $this->configureFormFieldVichImage(
            $form,
            'imageFile',
            'PARTNER_ENTITY.LABEL.IMAGE',
            'PARTNER_ENTITY.HELP.IMAGE',
            !($this->getSubject() && $this->getSubject()->getImage())
        );

        $this->configureFormSectionTranslations($form);
    }

    private function configureFormSectionTranslations(FormMapper $form): void
    {
        $form
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'          => 'FIELD.TRANSLATIONS',
                    'default_locale' => $this->parameterBag->get('defaultLocale'),
                    'locale_labels'  => $this->localeProvider->getLocaleTitlesForAdmin(),
                    'fields'         => [
                        'title' => [
                            'field_type'         => TextType::class,
                            'label'              => 'PARTNER_ENTITY.LABEL.TITLE',
                            'help'               => 'PARTNER_ENTITY.HELP.TITLE',
                            'required'           => false,
                            'translation_domain' => $this->getTranslationDomain(),
                            'constraints'        => [],
                        ],
                    ],
                    'excluded_fields' => ['createdAt', 'updatedAt'],
                ]
            );
    }
}
