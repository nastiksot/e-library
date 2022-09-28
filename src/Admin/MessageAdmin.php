<?php

declare(strict_types=1);

namespace App\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\CQ\Command\Translation\ClearTranslationCacheCommand;
use App\Entity\Message;
use App\Service\LocaleProvider\LocaleProvider;
use App\Service\MessageBusHandler;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @method Message|null getSubject()
 */
class MessageAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    private MessageBusHandler $messageBusHandler;

    protected LocaleProvider $localeProvider;

    #[Required]
    public function initDependencies(MessageBusHandler $messageBusHandler, LocaleProvider $localeProvider): void
    {
        $this->messageBusHandler = $messageBusHandler;
        $this->localeProvider    = $localeProvider;
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
            '_sort_by'    => 'code',
        ];
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('code', null, ['label' => 'MESSAGE_ENTITY.LABEL.CODE'])
            ->add('defaultValue', null, ['label' => 'MESSAGE_ENTITY.LABEL.DEFAULT_VALUE'])
            ->add('translations.value', null, ['label' => 'MESSAGE_ENTITY.LABEL.VALUE'])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'code', 'MESSAGE_ENTITY.LABEL.CODE', ['identifier' => true, 'route' => ['name' => 'edit']]);
        $this->configureListFieldText($list, 'defaultValue', 'MESSAGE_ENTITY.LABEL.DEFAULT_VALUE');
        $this->configureListFieldText($list, 'translate.value', 'MESSAGE_ENTITY.LABEL.VALUE');
        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with($this->trans('MESSAGE_ENTITY.SECTION.MAIN'));

        $form
            ->add(
                'code',
                TextType::class,
                [
                    'label'    => $this->trans('MESSAGE_ENTITY.LABEL.CODE'),
                    'required' => true,
                    'attr'     => ['readonly' => true],
                ]
            )
            ->add(
                'defaultValue',
                TextType::class,
                [
                    'label'    => $this->trans('MESSAGE_ENTITY.LABEL.DEFAULT_VALUE'),
                    'required' => true,
                    'attr'     => ['readonly' => true],
                ]
            )
            ->add(
                'translations',
                TranslationsType::class,
                [
                    'label'          => $this->trans('MESSAGE_ENTITY.LABEL.TRANSLATIONS'),
                    'default_locale' => $this->parameterBag->get('defaultLocale'),
                    'locale_labels'  => $this->localeProvider->getLocaleTitlesForAdmin(),
                    'fields'         => [
                        'value' => [
                            'field_type'         => TextType::class,
                            'label'              => 'MESSAGE_ENTITY.LABEL.VALUE',
                            'help'               => 'MESSAGE_ENTITY.HELP.VALUE',
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
        ;

        $form->end();
    }

    protected function postUpdate(object $object): void
    {
        $this->messageBusHandler->handleCommand(new ClearTranslationCacheCommand());
    }
}
