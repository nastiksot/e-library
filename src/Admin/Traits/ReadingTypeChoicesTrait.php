<?php

namespace App\Admin\Traits;

use App\Contracts\Dictionary\ReadingType;

trait ReadingTypeChoicesTrait
{
    public function getReadingTypeChoices(): array
    {
        $choices = [];
        foreach (ReadingType::toArray() as $key => $value) {
            $choices['READING_ENTITY.CHOICES.TYPE.' . $key] = $value;
        }

        return $choices;
    }
}
