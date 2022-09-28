<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class DealerUserAdmin extends AbstractUserAdmin
{
    protected function resolveUserRoles(): array
    {
        return UserInterface::AVAILABLE_DEALER_ROLES;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);

        if ($this->isChild()) {
            return;
        }

        // This is the route configuration as a parent
        $collection->clear();
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $this->configureFilterFieldText($filter, 'email', 'USER_ENTITY.LABEL.EMAIL');
        $this->configureFilterFieldRoles($filter);
        $this->configureFilterFieldActive($filter, null, 'USER_ENTITY.LABEL.ACTIVE');
        $this->configureFilterFieldText($filter, 'firstName', 'USER_ENTITY.LABEL.FIRST_NAME');
        $this->configureFilterFieldText($filter, 'lastName', 'USER_ENTITY.LABEL.LAST_NAME');
        $this->configureFilterFieldCreatedAtDateRange($filter);
        $this->configureFilterFieldUpdatedAtDateRange($filter);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'email', 'USER_ENTITY.LABEL.EMAIL', ['identifier' => true]);
        $this->configureListFieldText($list, 'firstName', 'USER_ENTITY.LABEL.FIRST_NAME');
        $this->configureListFieldText($list, 'lastName', 'USER_ENTITY.LABEL.LAST_NAME');
        $this->configureListFieldText(
            $list,
            'roles',
            'USER_ENTITY.LABEL.ROLES', ['template' => 'admin/CRUD/list__roles_user_entity.html.twig']
        );
        $this->configureListFieldCreatedAt($list);
        $this->configureListFieldUpdatedAt($list);
        $this->configureListFieldActive($list);
        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('USER_ENTITY.SECTION.DEALER');

        $this->configureFormFieldActive($form);

        $form->add(
            'email',
            TextType::class,
            [
                'required'    => true,
                'label'       => 'USER_ENTITY.LABEL.EMAIL',
                'help'        => 'USER_ENTITY.HELP.EMAIL',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
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
                    'type'          => PasswordType::class,
                    'required'      => $isPasswordRequired,
                    'first_options' => [
                        'label' => 'USER_ENTITY.LABEL.PASSWORD',
                        'attr'  => ['autocomplete' => 'new-password'],
                    ],
                    'second_options' => [
                        'label' => 'USER_ENTITY.LABEL.PASSWORD_REPEAT',
                        'attr'  => ['autocomplete' => 'new-password'],
                    ],
                    'invalid_message'    => $this->trans('USER_ENTITY.ERROR.PASSWORD_MISMATCH'),
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            );

        $form->add(
            'firstName',
            TextType::class,
            [
                'required'           => false,
                'label'              => 'USER_ENTITY.LABEL.FIRST_NAME',
                'help'               => 'USER_ENTITY.HELP.FIRST_NAME',
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
                'translation_domain' => $this->getTranslationDomain(),
            ]
        );

        $user = $this->getSubject();
        $form
            ->add(
                'role',
                ChoiceType::class,
                [
                    'data'               => $user?->getRole(),
                    'mapped'             => false,
                    'required'           => true,
                    'multiple'           => false,
                    'label'              => 'USER_ENTITY.LABEL.ROLES',
                    'help'               => 'USER_ENTITY.HELP.ROLES',
                    'empty_data'         => [UserInterface::ROLE_DEALER_EMPLOYEE],
                    'choices'            => $this->getRolesChoices(),
                    'constraints'        => [new NotBlank()],
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            );

        $form->end();
    }
}
