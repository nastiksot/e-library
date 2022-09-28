<?php

declare(strict_types=1);

namespace App\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Country;
use App\Entity\Language;
use App\Repository\CountryRepository;
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
 * @method Language|null getSubject()
 */
class LanguageAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    protected LocaleProvider $localeProvider;

    protected CountryRepository $countryRepository;

    private EntityManagerInterface $em;

    #[Required]
    public function initDependencies(EntityManagerInterface $em, LocaleProvider $localeProvider, CountryRepository $countryRepository): void
    {
        $this->em                = $em;
        $this->localeProvider    = $localeProvider;
        $this->countryRepository = $countryRepository;
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
            ->add('code', null, ['label' => 'LANGUAGE_ENTITY.LABEL.CODE'])
            ->add('translations.title', null, ['label' => 'LANGUAGE_ENTITY.LABEL.VALUE']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText(
            $list,
            'code',
            'LANGUAGE_ENTITY.LABEL.CODE',
            [
                'identifier'   => true,
                'route'        => ['name' => 'edit'],
                'header_style' => 'width:100px;',
            ]
        );
        $this->configureListFieldText($list, 'translate.title', 'LANGUAGE_ENTITY.LABEL.TITLE');
        $list->add('countries', null, [
            'label' => $this->trans('LANGUAGE_ENTITY.LABEL.COUNTRIES'),
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
        $form->with('LANGUAGE_ENTITY.SECTION.MAIN');

        $form
            ->add(
                'code',
                TextType::class,
                [
                    'label'    => 'LANGUAGE_ENTITY.LABEL.CODE',
                    'required' => true,
                    'attr'     => [
                        'readonly' => $this->getSubject()?->isGerman(),
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'          => 'LANGUAGE_ENTITY.LABEL.TRANSLATIONS',
                    'default_locale' => $this->parameterBag->get('defaultLocale'),
                    'locale_labels'  => $this->localeProvider->getLocaleTitlesForAdmin(),
                    'fields'         => [
                        'title' => [
                            'field_type'         => TextType::class,
                            'label'              => 'LANGUAGE_ENTITY.LABEL.TITLE',
                            'help'               => 'LANGUAGE_ENTITY.HELP.TITLE',
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
            ->add('countries', null,
            [
                'by_reference' => false,

                'label' => $this->trans('LANGUAGE_ENTITY.LABEL.COUNTRIES'),
                'help'  => $this->trans('LANGUAGE_ENTITY.HELP.COUNTRIES'),
            ]
        );

        $form->end();
    }

    /**
     * @param Language $object
     */
    protected function preRemove(object $object): void
    {
        if ($object->isGerman()) {
            // German is not allowed to delete => cancel remove action
            throw new Exception(Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED], Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    /**
     * @param Language $object
     */
    protected function preUpdate(object $object): void
    {
        if (!$object->isGerman()) {
            return;
        }

        $isGbCountryFound = false;

        foreach ($object->getCountries() as $country) {
            if ($country->isGermany()) {
                $isGbCountryFound = true;

                break;
            }
        }

        if (!$isGbCountryFound) {
            // relation between Germany and German cannot be deleted => recover it
            $gbCountry = $this->countryRepository->findOneBy(['id' => Country::COUNTRY_GERMANY_ID]);

            if ($gbCountry) {
                $object->addCountry($gbCountry);
            }
        }
    }

    public function preBatchAction(string $actionName, ProxyQueryInterface $query, array &$idx, bool $allElements = false): void
    {
        // German is not allowed to delete
        $idx = array_diff($idx, [Language::LANGUAGE_GERMAN_ID]);
    }
}
