<?php

declare(strict_types=1);

namespace App\Admin\Settings;

use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditGeneralSettingsAdmin extends AbstractSettingsEditAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $this->configureFormSectionMain($form);
    }

    protected function configureFormSectionMain(FormMapper $form): void
    {
        $form->with('GENERAL_SETTINGS_ENTITY.SECTION.MAIN');

        $this->configureFormFieldNumber(
            $form,
            'penalty',
            'GENERAL_SETTINGS_ENTITY.LABEL.PENALTY',
            'GENERAL_SETTINGS_ENTITY.HELP.PENALTY',
            false,
            [
                'scale' => 2,
                'constraints' => [new NotBlank()],
            ]
        );

        $this->configureFormFieldText(
            $form,
            'expireColor',
            'GENERAL_SETTINGS_ENTITY.LABEL.EXPIRE_COLOR',
            'GENERAL_SETTINGS_ENTITY.HELP.EXPIRE_COLOR',
            false,
            ['constraints' => [new NotBlank()]]
        );

        $form->end();
    }
}
