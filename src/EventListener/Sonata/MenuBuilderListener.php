<?php

declare(strict_types=1);

namespace App\EventListener\Sonata;

use App\Contracts\Entity\UserInterface;

//use App\Entity\MailTemplate\DealerRequestMailTemplate;
//use App\Entity\MailTemplate\ForgotPasswordMailTemplate;
//use App\Entity\MailTemplate\RegisterMailTemplate;
//use App\Entity\MailTemplate\RegisterNotificationMailTemplate;
use App\Entity\Settings\GeneralSettings;

//use App\Repository\MailTemplate\AbstractMailTemplateRepository;
use App\Repository\Settings\AbstractSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Event\ConfigureMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilderListener implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private AuthorizationCheckerInterface $authorizationChecker,
        private TranslatorInterface $translator,
        private string $adminTranslationDomain,
    ) {
    }

//
    public static function getSubscribedEvents(): array
    {
        return [
            ConfigureMenuEvent::SIDEBAR => 'addMenuItems',
        ];
    }

    private function isUser(): bool
    {
        return $this->authorizationChecker->isGranted(UserInterface::ROLE_USER);
    }

//    private function isAdmin(): bool
//    {
//        return $this->authorizationChecker->isGranted(UserInterface::ROLE_ADMIN);
//    }
//
//    private function isEditor(): bool
//    {
//        return $this->authorizationChecker->isGranted(UserInterface::ROLE_EDITOR);
//    }
//
    public function addMenuItems(ConfigureMenuEvent $event): void
    {
        // if ($this->isAdmin()) {
//        $this->addMenuSettings($event);
//        $this->addMenuMenuMailTemplate($event);
        // }

        if (!$this->isUser()) {
            $this->addMenuUndefinedUser($event);
//        } else {
//            $this->addMenuUser($event);
        }
    }

    private function addMenuUndefinedUser(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu()->addChild('MENU_GROUP.USER');

        $menu->addChild(
            'register',
            [
                'label' => $this->translator->trans('SECURITY.REGISTER', [], $this->adminTranslationDomain),
                'route' => 'register',
            ]
        );

        $menu->addChild(
            'admin.login',
            [
                'label' => $this->translator->trans('SECURITY.LOGIN', [], $this->adminTranslationDomain),
                'route' => 'admin.login',
            ]
        );


    }

    private function addMenuSettings(ConfigureMenuEvent $event): void
    {
        // get menu group
        $menu = $event->getMenu()->getChild('MENU_GROUP.SETTINGS');

        if (!$menu) {
            return;
        }

        // add sub menu
        $menu->setDisplay(true);
        $this->addSubMenuSettings(
            $menu,
            'MENU.SETTINGS.GENERAL',
            'admin_general_settings_edit',
            GeneralSettings::class
        );
    }

    private function addSubMenuSettings(
        ItemInterface $menuItem,
        string $label,
        string $route,
        string $settingsEntityClass
    ): void {
        /** @var AbstractSettingsRepository $settingsRepository */
        $settingsRepository = $this->em->getRepository($settingsEntityClass);

        // get settings
        $settings = $settingsRepository->getSettings();

        if (!$settings) {
            return;
        }

        // add settings sub menu
        $menuItem
            ->addChild(
                $label,
                [
                    'label' => $this->translator->trans($label, [], $this->adminTranslationDomain,),
                    'route' => $route,
                    'routeParameters' => ['id' => $settings->getId()],
                ]
            );
    }
//
//    private function addMenuMenuMailTemplate(ConfigureMenuEvent $event): void
//    {
//        // get menu group
//        $menu = $event->getMenu()->getChild('MENU_GROUP.MAIL_TEMPLATE');
//
//        if (!$menu) {
//            return;
//        }
//
//        // add sub menu
//        $menu->setDisplay(true);
//
//        $this->addSubMenuMailTemplate(
//            $menu,
//            'MENU.MAIL_TEMPLATE.REGISTER',
//            'admin_register_mail_template_edit',
//            RegisterMailTemplate::class
//        );
//
//        $this->addSubMenuMailTemplate(
//            $menu,
//            'MENU.MAIL_TEMPLATE.REGISTER_NOTIFICATION',
//            'admin_register_notification_mail_template_edit',
//            RegisterNotificationMailTemplate::class
//        );
//
//        $this->addSubMenuMailTemplate(
//            $menu,
//            'MENU.MAIL_TEMPLATE.FORGOT_PASSWORD',
//            'admin_forgot_password_mail_template_edit',
//            ForgotPasswordMailTemplate::class
//        );
//
//        $this->addSubMenuMailTemplate(
//            $menu,
//            'MENU.MAIL_TEMPLATE.DEALER_REQUEST',
//            'admin_dealer_request_mail_template_edit',
//            DealerRequestMailTemplate::class
//        );
//    }
//
//    private function addSubMenuMailTemplate(
//        ItemInterface $menuItem,
//        string $label,
//        string $route,
//        string $settingsEntityClass
//    ): void {
//        /** @var AbstractMailTemplateRepository $templateRepository */
//        $templateRepository = $this->em->getRepository($settingsEntityClass);
//
//        // get template
//        $template = $templateRepository->getTemplate();
//
//        if (!$template) {
//            return;
//        }
//
//        // add settings sub menu
//        $menuItem
//            ->addChild(
//                $label,
//                [
//                    'label'           => $this->translator->trans($label, [], $this->adminTranslationDomain, ),
//                    'route'           => $route,
//                    'routeParameters' => ['id' => $template->getId()],
//                ]
//            );
//    }
}
