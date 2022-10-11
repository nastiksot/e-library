<?php

declare(strict_types=1);

namespace App\Form\Type\Reading;

use Carbon\Carbon;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProlongReadingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'prolong_at',
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
