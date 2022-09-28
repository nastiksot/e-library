<?php

declare(strict_types=1);

namespace App\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegisterUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, ['constraints' => new NotBlank()])
            ->add('last_name', TextType::class, ['constraints' => new NotBlank()])
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [new NotBlank(), new Email(), new Callback([$this, 'validateFormFieldEmail'])],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'constraints' => [new NotBlank()],
                ]
            );
    }


    public function validateFormFieldEmail($value, ExecutionContextInterface $context): void
    {
        if ($value) {
            $context
                ->buildViolation('EMAIL_TAKEN')
                ->setParameter('%EMAIL%', $value)
                ->atPath('email')->addViolation();
        }
    }
}
