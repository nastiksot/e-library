<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Contracts\Entity\UserInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminUserAdmin extends AbstractUserAdmin
{
    protected function resolveUserRoles(): array
    {
        return [
            UserInterface::ROLE_SUPER_ADMIN,
            UserInterface::ROLE_ADMIN,
            UserInterface::ROLE_EDITOR,
            //UserInterface::ROLE_USER,
        ];
    }

    protected function configureFormFields(FormMapper $form): void
    {
        parent::configureFormFields($form);
        $this->configureFormFieldsGoogleAuthenticator($form);
    }

    protected function configureFormFieldsGoogleAuthenticator(FormMapper $form): void
    {
        $form
            ->add(
                'googleAuthenticatorEnabled',
                ChoiceFieldMaskType::class,
                [
                    'label'   => 'USER_ENTITY.LABEL.GOOGLE_AUTHENTICATOR_ENABLED',
                    'choices' => self::$choicesYesNo,
                    'map'     => [
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
    }
}
