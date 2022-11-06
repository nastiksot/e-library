<?php

declare(strict_types=1);

namespace App\Admin\User\Profile;

use App\Admin\Traits\ConfigureFormTrait;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EditUserProfileAdmin extends AbstractUserProfileAdmin
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
                'constraints'        => [new NotBlank(), new Email(), new Callback([$this, 'validateFormFieldEmail'])],
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

        $form->end();
    }

    public function validateFormFieldEmail($value, ExecutionContextInterface $context): void
    {
        if ($value) {
            $profileUser = $this->getSubject();
            $existsUser  = $this->userRepository->getOneByIdentifier($value);

            if ($profileUser && $existsUser &&
                $profileUser->getId() !== $existsUser->getId()
            ) {
                $context
                    ->buildViolation('USER_ENTITY.ERROR.EMAIL_TAKEN')
                    ->setParameter('%EMAIL%', $value)
                    ->setTranslationDomain($this->getTranslationDomain())
                    ->atPath('email')->addViolation();
            }
        }
    }
}
