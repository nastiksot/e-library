<?php

namespace App\Admin\Traits;

use App\Contracts\Dictionary\OrderStatus;

trait OrderStatusChoicesTrait
{
    public function getOrderStatusChoices(): array
    {
        $choices = [];
        foreach (OrderStatus::toArray() as $key => $value) {
            $choices['ORDER_ENTITY.CHOICES.STATUS.' . $key] = $value;
        }

        return $choices;
    }
}
