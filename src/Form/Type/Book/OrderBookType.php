<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Admin\Traits\ReadingTypeChoicesTrait;
use Carbon\Carbon;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderBookType extends AbstractType
{
    use ReadingTypeChoicesTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                DatePickerType::class,
                [
                    'widget'      => 'single_text',
                    'required'    => false,
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                    ],
                ]
            )
            ->add(
                'end_at',
                DatePickerType::class,
                [
                    'widget'      => 'single_text',
                    'required'    => false,
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                    ],
                ]
            );
    }
}
