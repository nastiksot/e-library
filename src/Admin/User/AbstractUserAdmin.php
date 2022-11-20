<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Contracts\Entity\UserInterface;
use App\CQ\Query\Reading\GetTotalPenaltyReadingQuery;
use App\Repository\User\UserRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Filter\Model\FilterData;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Service\Attribute\Required;

use function current;
use function in_array;
use function strtoupper;

/**
 * @method UserInterface|null getSubject()
 */
abstract class AbstractUserAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    abstract protected function resolveUserRoles(): array;

    protected UserRepository $userRepository;

    #[Required]
    public function setUserRepository(
        UserRepository $userRepository
    ): void {
        $this->userRepository = $userRepository;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues = [
            '_sort_order' => 'DESC',
            '_sort_by'    => 'createdAt',
        ];
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        /** @var ProxyQueryInterface|QueryBuilder $query */
        parent::configureQuery($query);

        $alias      = current($query->getRootAliases());
        $conditions = ["{$alias}.roles = 'ROLES_ARE_NOT_SET'"];

        if ($roles = $this->resolveUserRoles()) {
            $conditions = [];

            foreach ($roles as $key => $role) {
                $conditions[] = "{$alias}.roles LIKE :role_{$key}";
                $query->setParameter(":role_{$key}", '%' . $role . '%');
            }
        }
        $query->andWhere($query->expr()->orX(...$conditions));

        return $query;
    }

    protected function getRolesChoices(): array
    {
        //return [];
        $choices = [];
        $roles   = $this->resolveUserRoles();

        foreach ($roles as $role) {
            $choices[$this->trans('USER_ENTITY.ROLE.' . strtoupper($role))] = $role;
        }

        return $choices;
    }

    protected function configureFilterFieldRoles(
        DatagridMapper $filter,
        ?string $name = null,
        array $choices = [],
        ?string $label = null,
        array $options = []
    ): void {
        $name    = $name ?? 'roles';
        $choices = $choices ?: $this->getRolesChoices();
        $label   = $label ?? 'USER_ENTITY.LABEL.ROLES';

        $filter->add(
            $name,
            CallbackFilter::class,
            [
                'label'         => $label,
                'field_type'    => ChoiceType::class,
                'field_options' => [
                    'choices' => $choices,
                    //'translation_domain' => $this->getTranslationDomain(),
                ],
                'callback'      => static function (
                    ProxyQuery|QueryBuilder $qb,
                    string $alias,
                    string $field,
                    FilterData $data
                ) {
                    if ($data->hasValue() && $role = $data->getValue()) {
                        $qb->andWhere(sprintf('%s.%s LIKE :role ', $alias, $field))
                            ->setParameter('role', '%' . (new UnicodeString($role))->toString() . '%');

                        return true;
                    }

                    return false;
                },
            ]
        );
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $this->configureFilterFieldText($filter, 'id', 'USER_ENTITY.LABEL.ID');
        $this->configureFilterFieldText($filter, 'email', 'USER_ENTITY.LABEL.EMAIL');
        $this->configureFilterFieldText($filter, 'firstName', 'USER_ENTITY.LABEL.FIRST_NAME');
        $this->configureFilterFieldText($filter, 'lastName', 'USER_ENTITY.LABEL.LAST_NAME');
        $this->configureFilterFieldRoles($filter);
        $this->configureFilterFieldActive($filter, null, 'USER_ENTITY.LABEL.ACTIVE');
        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'email', 'USER_ENTITY.LABEL.EMAIL');
        $this->configureListFieldText($list, 'firstName', 'USER_ENTITY.LABEL.FIRST_NAME');
        $this->configureListFieldText($list, 'lastName', 'USER_ENTITY.LABEL.LAST_NAME');

        $this->configureListFieldText(
            $list,
            'penalty',
            'READING_ENTITY.LABEL.PENALTY',
            [
                'virtual_field' => true,
                'template'      => 'admin/user/list__field_penalty.html.twig',
                'data'          => $this->listTemplateData(),
            ]
        );

        $this->configureListFieldCreatedAt($list);
        $this->configureListFieldUpdatedAt($list);
        $this->configureListFieldActive($list);

        $actions = ['edit' => [], 'delete' => ['template' => 'admin/user/list__action_delete.html.twig']];
        $this->configureListFieldActions($list, $actions);
    }

    private function listTemplateData(): array
    {
        /** @var ProxyQueryInterface|QueryBuilder $query */
        $qb    = $this->createQuery();
        $alias = current($qb->getRootAliases());

        // collect all ids that were displayed by filtering
        $ids = $qb->select("{$alias}.id")->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR_COLUMN);

        // get total penalty for displayed data grid
        $totalPenalty = $this->messageBusHandler->handleQuery(new GetTotalPenaltyReadingQuery(userIds: $ids));

        return [
            'total_penalty' => $totalPenalty,
        ];
    }


    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('USER_ENTITY.SECTION.MAIN');
        $form->add(
            'firstName',
            TextType::class,
            [
                'required'           => false,
                'label'              => 'USER_ENTITY.LABEL.FIRST_NAME',
                'help'               => 'USER_ENTITY.HELP.FIRST_NAME',
                'constraints'        => [new NotBlank()],
                'translation_domain' => $this->getTranslationDomain(),
            ]
        );

        $form->add(
            'lastName',
            TextType::class,
            [
                'required'           => false,
                'label'              => 'USER_ENTITY.LABEL.LAST_NAME',
                'help'               => 'USER_ENTITY.HELP.LAST_NAME',
                'constraints'        => [new NotBlank()],
                'translation_domain' => $this->getTranslationDomain(),
            ]
        );

        $form->end();

        $form->with('USER_ENTITY.SECTION.CREDENTIALS');
        $form->add(
            'email',
            TextType::class,
            [
                'required'           => false,
                'label'              => 'USER_ENTITY.LABEL.EMAIL',
                'help'               => 'USER_ENTITY.HELP.EMAIL',
                'constraints'        => [
                    new NotBlank(),
                    new Callback([$this, 'validateFormFieldUserEmail']),
                ],
                'translation_domain' => $this->getTranslationDomain(),
            ]
        );

        $user               = $this->getSubject();
        $isPasswordRequired = $user && !$user->getId();

        $form
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type'               => PasswordType::class,
                    'required'           => $isPasswordRequired,
                    'first_options'      => [
                        'label' => 'USER_ENTITY.LABEL.PASSWORD',
                        'attr'  => ['autocomplete' => 'new-password'],
                    ],
                    'second_options'     => [
                        'label' => 'USER_ENTITY.LABEL.PASSWORD_REPEAT',
                        'attr'  => ['autocomplete' => 'new-password'],
                    ],
                    'invalid_message'    => $this->trans(
                        'USER_ENTITY.ERROR.PASSWORD_MISMATCH',
                        [],
                        $this->getTranslationDomain()
                    ),
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            );

        // roles
        if ($rolesChoices = $this->getRolesChoices()) {
            $isMultiple = true;

            if ($isMultiple) {
                $form
                    ->add(
                        'roles',
                        ChoiceType::class,
                        [
                            'required'           => true,
                            'multiple'           => true,
                            'label'              => 'USER_ENTITY.LABEL.ROLES',
                            'help'               => 'USER_ENTITY.HELP.ROLES',
                            'choices'            => $rolesChoices,
                            'constraints'        => [new NotBlank()],
                            'translation_domain' => $this->getTranslationDomain(),
                        ]
                    );
            } else {
                $user = $this->getSubject();
                $form
                    ->add(
                        'role',
                        ChoiceType::class,
                        [
                            'data'               => $user ? $user->getRole() : null,
                            'mapped'             => false,
                            'required'           => true,
                            'multiple'           => false,
                            'label'              => 'USER_ENTITY.LABEL.ROLES',
                            'help'               => 'USER_ENTITY.HELP.ROLES',
                            'empty_data'         => [UserInterface::ROLE_USER],
                            'choices'            => $this->getRolesChoices(),
                            'constraints'        => [new NotBlank()],
                            'translation_domain' => $this->getTranslationDomain(),
                        ]
                    );
            }
        }

        $form->end();
    }

    protected function configureFormFieldsGoogleAuthenticator(FormMapper $form): void
    {
        $form->with('USER_ENTITY.SECTION.GOOGLE_AUTH');
        $form
            ->add(
                'googleAuthenticatorEnabled',
                ChoiceFieldMaskType::class,
                [
                    'label'              => 'USER_ENTITY.LABEL.GOOGLE_AUTHENTICATOR_ENABLED',
                    'choices'            => self::$choicesYesNo,
                    'map'                => [
                        1 => ['googleAuthenticatorToken'],
                        0 => [],
                    ],
                    'required'           => true,
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            )
            ->add(
                'googleAuthenticatorToken',
                TextType::class,
                [
                    'label'    => 'USER_ENTITY.LABEL.GOOGLE_AUTHENTICATOR_TOKEN',
                    'required' => false,
                ]
            );
        $form->end();
    }

    public function validateFormFieldUserEmail($value, ExecutionContextInterface $context): void
    {
        if (null === $value) {
            return;
        }

        $entity = $this->userRepository->getOneByIdentifier($value);

        if (null !== $entity &&
            null !== $this->getSubject() &&
            $entity->getId() !== $this->getSubject()->getId()
        ) {
            $context
                ->buildViolation('USER_ENTITY.ERROR.EMAIL_TAKEN')
                ->setParameter('%EMAIL%', $value)
                ->setTranslationDomain($this->getTranslationDomain())
                ->atPath('email')->addViolation();
        }
    }

    /**
     * @param UserInterface $object
     */
    public function prePersist($object): void
    {
        $this->updateRoles($object);
    }

    /**
     * @param UserInterface $object
     */
    public function preUpdate($object): void
    {
        $this->updateRoles($object);
    }

    protected function updateRoles(UserInterface $user): void
    {
        if (false === $this->getRequest()->isXmlHttpRequest() &&
            $this->hasSubject() &&
            ($form = $this->getForm()) &&
            $form->has('role') &&
            ($role = $form->get('role')->getData()) &&
            in_array($role, $this->getRolesChoices(), true)
        ) {
            $user->setRoles([$role]);
        }
    }
}
