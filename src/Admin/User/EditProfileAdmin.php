<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureFormTrait;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProfileAdmin extends AbstractAdmin
{
    use ConfigureFormTrait;

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->clearExcept(['edit']);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('USER_ENTITY.SECTION.MAIN');
        $form->add(
            'firstName',
            TextType::class,
            [
                'required'           => true,
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
                'required'           => true,
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
                'required'           => true,
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
                    'invalid_message'    => 'USER_ENTITY.ERROR.PASSWORD_MISMATCH',
                    'translation_domain' => $this->getTranslationDomain(),
                ]
            );

        $form->end();
    }

}
