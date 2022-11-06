<?php

declare(strict_types=1);

namespace App\Form\Type\Reading;

use Carbon\Carbon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
