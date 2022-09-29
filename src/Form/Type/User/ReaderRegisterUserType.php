<?php

declare(strict_types=1);

namespace App\Form\Type\User;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReaderRegisterUserType extends AbstractUserType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, ['required' => false, 'constraints' => new NotBlank()])
            ->add('last_name', TextType::class, ['required' => false, 'constraints' => new NotBlank()])
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => false,
                    'constraints' => [new NotBlank(), new Email(), new Callback([$this, 'validateFormFieldEmail'])],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'required' => false,
                    'constraints' => [new NotBlank()],
                ]
            );
    }

}
