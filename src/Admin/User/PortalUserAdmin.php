<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PortalUserAdmin extends AbstractUserAdmin
{
    protected function resolveUserRoles(): array
    {
        return [
            UserInterface::ROLE_USER,
        ];
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        parent::configureDatagridFilters($filter);
        $this->configureFilterFieldText($filter, 'firstName', 'USER_ENTITY.LABEL.FIRST_NAME');
        $this->configureFilterFieldText($filter, 'lastName', 'USER_ENTITY.LABEL.LAST_NAME');
        $this->configureFilterFieldActive($filter, 'acceptNews', 'USER_ENTITY.LABEL.ACCEPT_NEWS');
        $this->configureFilterFieldActive(
            $filter,
            'acceptProcessPersonalData',
            'USER_ENTITY.LABEL.ACCEPT_PROCESS_PERSONAL_DATA'
        );
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'email', 'USER_ENTITY.LABEL.EMAIL');
        $this->configureListFieldText($list, 'firstName', 'USER_ENTITY.LABEL.FIRST_NAME');
        $this->configureListFieldText($list, 'lastName', 'USER_ENTITY.LABEL.LAST_NAME');
        $this->configureListFieldCreatedAt($list);
        $this->configureListFieldUpdatedAt($list);
        $this->configureListFieldActive($list, 'acceptNews', 'USER_ENTITY.LABEL.ACCEPT_NEWS');
        $this->configureListFieldActive(
            $list,
            'acceptProcessPersonalData',
            'USER_ENTITY.LABEL.ACCEPT_PROCESS_PERSONAL_DATA'
        );
        $this->configureListFieldActive($list);
        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('USER_ENTITY.SECTION.PORTAL');

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

        $this->configureFormFieldActive($form);
        $this->configureFormFieldYesNo($form, 'acceptNews', 'USER_ENTITY.LABEL.ACCEPT_NEWS');
        $this->configureFormFieldYesNo(
            $form,
            'acceptProcessPersonalData',
            'USER_ENTITY.LABEL.ACCEPT_PROCESS_PERSONAL_DATA'
        );

        $form->end();

        parent::configureFormFields($form);
    }
}
