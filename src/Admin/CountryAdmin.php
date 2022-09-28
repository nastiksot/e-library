<?php

declare(strict_types=1);

namespace App\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Country;
use App\Entity\Language;
use App\Repository\LanguageRepository;
use App\Service\LocaleProvider\LocaleProvider;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use function array_diff;

/**
 * @method Country|null getSubject()
 */
class CountryAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected LocaleProvider $localeProvider;

    protected LanguageRepository $languageRepository;

    private EntityManagerInterface $em;

    #[Required]
    public function initDependencies(
        EntityManagerInterface $em,
        LocaleProvider $localeProvider,
        LanguageRepository $languageRepository,
    ): void {
        $this->em                 = $em;
        $this->localeProvider     = $localeProvider;
        $this->languageRepository = $languageRepository;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues = [
            '_sort_order' => 'ASC',
            '_sort_by'    => 'code',
        ];
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('code', null, ['label' => 'COUNTRY_ENTITY.LABEL.CODE'])
            ->add('translations.title', null, ['label' => 'COUNTRY_ENTITY.LABEL.VALUE']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText(
            $list,
            'code',
            'COUNTRY_ENTITY.LABEL.CODE',
            [
                'identifier'   => true,
                'route'        => ['name' => 'edit'],
                'header_style' => 'width:100px;',
            ]
        );
        $this->configureListFieldText($list, 'translate.title', 'COUNTRY_ENTITY.LABEL.TITLE');
        $list->add('languages', null, [
            'label' => $this->trans('COUNTRY_ENTITY.LABEL.LANGUAGES'),
        ]);
        $this->configureListFieldActive($list);
        $actions = [
            'edit'   => [],
            'delete' => [
                'template' => 'admin/CRUD/list__action_delete.html.twig',
            ],
        ];
        $this->configureListFieldActions($list, $actions);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('COUNTRY_ENTITY.SECTION.MAIN');

        $form
            ->add(
                'code',
                TextType::class,
                [
                    'label'    => 'COUNTRY_ENTITY.LABEL.CODE',
                    'required' => true,
                    'attr'     => [
                        'readonly' => $this->getSubject()?->isGermany(),
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'cartAddProductsUrl',
                TextType::class,
                [
                    'required' => true,
                    'label'    => 'COUNTRY_ENTITY.LABEL.CART_ADD_PRODUCTS_URL',
                    'help'     => 'COUNTRY_ENTITY.HELP.CART_ADD_PRODUCTS_URL',
                ],
            )
            ->add(
                'dealerSearchUrl',
                TextType::class,
                [
                    'required' => true,
                    'label'    => 'COUNTRY_ENTITY.LABEL.DEALER_SEARCH_URL',
                    'help'     => 'COUNTRY_ENTITY.HELP.DEALER_SEARCH_URL',
                ],
            )
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'          => 'COUNTRY_ENTITY.LABEL.TRANSLATIONS',
                    'default_locale' => $this->parameterBag->get('defaultLocale'),
                    'locale_labels'  => $this->localeProvider->getLocaleTitlesForAdmin(),
                    'fields'         => [
                        'title' => [
                            'field_type'         => TextType::class,
                            'label'              => 'COUNTRY_ENTITY.LABEL.TITLE',
                            'help'               => 'COUNTRY_ENTITY.HELP.TITLE',
                            'required'           => true,
                            'translation_domain' => $this->getTranslationDomain(),
                            'constraints'        => [
                                new NotBlank(),
                            ],
                        ],
                    ],
                    'excluded_fields' => ['createdAt', 'updatedAt'],
                ]
            )
            ->add('languages', null,
            [
                'by_reference' => false,

                'label' => $this->trans('COUNTRY_ENTITY.LABEL.LANGUAGES'),
                'help'  => $this->trans('COUNTRY_ENTITY.HELP.LANGUAGES'),
            ]
        );

        $form->end();
    }

    /**
     * @param Country $object
     *
     * @throws Exception
     */
    protected function preRemove(object $object): void
    {
        if ($object->isGermany()) {
            // Germany is not allowed to delete => cancel remove action
            throw new Exception(Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED], Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    /**
     * @param Country $object
     */
    protected function preUpdate(object $object): void
    {
        if (!$object->isGermany()) {
            return;
        }

        $isEnLanguageFound = false;

        foreach ($object->getLanguages() as $language) {
            if ($language->isGerman()) {
                $isEnLanguageFound = true;

                break;
            }
        }

        if (!$isEnLanguageFound) {
            // relation between Germany and German cannot be deleted => recover it
            $enLanguage = $this->languageRepository->findOneBy(['id' => Language::LANGUAGE_GERMAN_ID]);

            if ($enLanguage) {
                $object->addLanguage($enLanguage);
            }
        }
    }

    public function preBatchAction(string $actionName, ProxyQueryInterface $query, array &$idx, bool $allElements = false): void
    {
        // Germany is not allowed to delete
        $idx = array_diff($idx, [Country::COUNTRY_GERMANY_ID]);
    }
}
