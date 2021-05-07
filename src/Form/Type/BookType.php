<?php
declare(strict_types=1);

namespace App\Form\Type;

use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookType extends AbstractEntityType
{

    protected UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'constraints' => new NotBlank()
                ]
            )
            ->add(
                'authors',
                ChoiceType::class,
                [
                    'multiple' => true,
                    'choices' => [
                        'option-1' => 'value-1',
                        'option-2' => 'value-2',
                        'option-3' => 'value-3',
                    ]
                ]
            );
    }
}
