<?php

declare(strict_types=1);

namespace App\Admin\Settings;

use Sonata\AdminBundle\Form\FormMapper;

class EditGeneralSettingsAdmin extends AbstractSettingsEditAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $this->configureFormSectionSocial($form);
    }

    protected function configureFormSectionSocial(FormMapper $form): void
    {
        $form->with('GENERAL_SETTINGS_ENTITY.SECTION.SOCIAL');

        $this->configureFormFieldText(
            $form,
            'socialFacebook',
            'GENERAL_SETTINGS_ENTITY.LABEL.SOCIAL_FACEBOOK',
            'GENERAL_SETTINGS_ENTITY.HELP.SOCIAL_FACEBOOK'
        );

        $this->configureFormFieldText(
            $form,
            'socialYoutube',
            'GENERAL_SETTINGS_ENTITY.LABEL.SOCIAL_YOUTUBE',
            'GENERAL_SETTINGS_ENTITY.HELP.SOCIAL_YOUTUBE'
        );

        $this->configureFormFieldText(
            $form,
            'socialInstagram',
            'GENERAL_SETTINGS_ENTITY.LABEL.SOCIAL_INSTAGRAM',
            'GENERAL_SETTINGS_ENTITY.HELP.SOCIAL_INSTAGRAM'
        );

        $form->end();
    }
}
