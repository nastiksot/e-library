<?php

declare(strict_types=1);

namespace App\Form\Type\User;

use App\Repository\User\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

abstract class AbstractUserType extends AbstractType
{

    public function __construct(
        private string $adminTranslationDomain
    ) {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('userRepository');
        $resolver->setAllowedTypes('userRepository', UserRepository::class);
    }

    public function isIdentifierExists(string $value, ExecutionContextInterface $context): bool
    {
        /** @var FormInterface $form */
        $form = $context->getRoot();

        /** @var UserRepository $userRepository */
        $userRepository = $form->getConfig()->getOption('userRepository');

        return $userRepository->getOneByIdentifier($value) !== null;
    }

    public function validateFormFieldEmail($value, ExecutionContextInterface $context): void
    {
        if ($value && $this->isIdentifierExists($value, $context)) {
            $context
                ->buildViolation('USER_ENTITY.ERROR.EMAIL_TAKEN')
                ->setParameter('%EMAIL%', $value)
                ->setTranslationDomain($this->adminTranslationDomain)
                ->atPath('email')->addViolation();
        }
    }
}
