<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Admin\Traits\ReadingTypeChoicesTrait;
use Carbon\Carbon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderBookType extends AbstractType
{
    use ReadingTypeChoicesTrait;

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired(['book_id', 'user_id']);
        $resolver->setAllowedTypes('book_id', ['int']);
        $resolver->setAllowedTypes('user_id', ['int']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'book_id',
                HiddenType::class,
                [
                    'data' => $options['book_id'],
                ]
            )
            ->add(
                'user_id',
                HiddenType::class,
                [
                    'data' => $options['user_id'],
                ]
            )
            ->add(
                'quantity',
                NumberType::class,
                [
                    'required'    => false,
                    'empty_data'  => 1,
                    'scale'       => 0,
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(['value' => 1]),
                    ],
                ]
            )
            ->add(
                'reading_type',
                ChoiceType::class,
                [
                    'required'           => false,
                    'choices'            => $this->getReadingTypeChoices(),
                    'constraints'        => [new NotBlank()],
                    'translation_domain' => 'SonataAdminBundle',
                ]
            )
            ->add(
                'start_at',
                DateType::class,
                [
                    'required'    => false,
                    'widget'      => 'single_text',
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                    ],
                ]
            )
            ->add(
                'end_at',
                DateType::class,
                [
                    'required'    => false,
                    'widget'      => 'single_text',
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                    ],
                ]
            );
    }
}
