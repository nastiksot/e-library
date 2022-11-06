<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Admin\Traits\ReadingTypeChoicesTrait;
use Carbon\Carbon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
                    'attr'        => ['class' => 'w-150'],
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(1),
                    ],
                ]
            )
            ->add(
                'reading_type',
                ChoiceType::class,
                [
                    'required'           => false,
                    'choices'            => $this->getReadingTypeChoices(),
                    'attr'               => ['class' => 'w-150', 'data-sonata-select2' => 'false'],
                    'constraints'        => [new NotBlank()],
                    'translation_domain' => 'SonataAdminBundle',
                ]
            )
            ->add(
                'start_at',
                DateType::class,
                [
                    'widget'      => 'single_text',
                    'required'    => false,
                    'attr'        => ['min' => Carbon::today()->format('Y-m-d'), 'class' => 'w-150'],
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
                    'widget'      => 'single_text',
                    'required'    => false,
                    'attr'        => ['min' => Carbon::today()->format('Y-m-d'), 'class' => 'w-150'],
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(['value' => Carbon::today()->startOfDay()]),
                    ],
                ]
            );
    }
}
