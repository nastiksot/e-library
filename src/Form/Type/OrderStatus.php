<?php
declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Order;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderStatus extends AbstractEntityType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'status',
                ChoiceType::class,
                [
                    'choices' => array_flip(Order::STATUSES),
                ]
            );
    }
}
