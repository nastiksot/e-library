<?php

declare(strict_types=1);

namespace App\EventListener\Sonata;

use App\Contracts\Entity\UserInterface;
use App\Entity\Settings\GeneralSettings;
use App\Repository\Settings\AbstractSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Event\ConfigureMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilderListener implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private AuthorizationCheckerInterface $authorizationChecker,
        private TokenStorageInterface $tokenStorage,
        private TranslatorInterface $translator,
        private string $adminTranslationDomain,
    ) {
    }

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

    private function isAdmin(): bool
    {
        return $this->authorizationChecker->isGranted(UserInterface::ROLE_ADMIN);
    }

    private function isEditor(): bool
    {
        return $this->authorizationChecker->isGranted(UserInterface::ROLE_EDITOR);
    }

    private function getUser(): UserInterface|\Symfony\Component\Security\Core\User\UserInterface
    {
        return $this->tokenStorage->getToken()?->getUser();
    }

    public function addMenuItems(ConfigureMenuEvent $event): void
    {
        // create menu to undefined user
        if (!$this->isUser()) {
            $this->addMenuUndefinedUser($event);

            return;
        }

        // create menu to logged-in user
        // change ico
        $menu = $event->getMenu()->getChild('MENU_GROUP.USER');
        if ($menu) {
            $menu->setExtra('icon', '<i class="fa fa-users"></i>');
        }

        $menu = $event->getMenu()->getChild('MENU_GROUP.BOOK');
        if ($menu) {
            $menu->setExtra('icon', '<i class="fa fa-book"></i>');
        }

        // create profile menu
        $this->addMenuProfileUser($event);

        // create settings menu
        if ($this->isAdmin()) {
            $this->addMenuSettings($event);
        }
    }

    private function addMenuUndefinedUser(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu()->addChild(
            'MENU_GROUP.USER_UNDEFINED',
            [
                'label' => $this->translator->trans('MENU_GROUP.USER_UNDEFINED', [], $this->adminTranslationDomain),
            ]
        );
        $menu->setExtras(['icon' => '<i class="fa fa-folder"></i>']);

        $menu->addChild(
            'MENU.USER_UNDEFINED.REGISTER',
            [
                'label' => $this->translator->trans('MENU.USER_UNDEFINED.REGISTER', [], $this->adminTranslationDomain),
                'route' => 'admin_user_reader_register',
            ]
        );

        $menu->addChild(
            'MENU.USER_UNDEFINED.LOGIN',
            [
                'label' => $this->translator->trans('MENU.USER_UNDEFINED.LOGIN', [], $this->adminTranslationDomain),
                'route' => 'admin_user_reader_login',
            ]
        );
    }

    private function addMenuProfileUser(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu()->addChild(
            'MENU_GROUP.USER_PROFILE',
            [
                'label' => $this->translator->trans('MENU_GROUP.USER_PROFILE', [], $this->adminTranslationDomain),
                'icon'  => '<i class="fa fa-user"></i>',
            ]
        );
        $menu->setExtra('icon', '<i class="fa fa-user"></i>');

        $menu->addChild(
            'MENU.USER_PROFILE.ORDER',
            [
                'label' => $this->translator->trans('MENU.USER_PROFILE.ORDER', [], $this->adminTranslationDomain),
                'route' => 'admin_user_order_list',
            ]
        );

        $menu->addChild(
            'MENU.USER_PROFILE.READING',
            [
                'label' => $this->translator->trans('MENU.USER_PROFILE.READING', [], $this->adminTranslationDomain),
                'route' => 'admin_user_reading_list',
            ]
        );

        $menu->addChild(
            'MENU.USER_PROFILE.EDIT',
            [
                'label'           => $this->translator->trans(
                    'MENU.USER_PROFILE.EDIT',
                    [],
                    $this->adminTranslationDomain
                ),
                'route'           => 'admin_user_profile_edit',
                'routeParameters' => ['id' => $this->getUser()->getId()],
            ]
        );

        $menu->addChild(
            'MENU.USER_PROFILE.LOGOUT',
            [
                'label' => $this->translator->trans('MENU.USER_PROFILE.LOGOUT', [], $this->adminTranslationDomain),
                'route' => 'admin.logout',
            ]
        );
    }

    private function addMenuSettings(ConfigureMenuEvent $event): void
    {
        // get menu group
        $menu = $event->getMenu()->getChild('MENU_GROUP.SETTINGS');

        if (!$menu) {
            $menu = $event->getMenu()->addChild(
                'MENU_GROUP.SETTINGS',
                [
                    'label' => $this->translator->trans('MENU_GROUP.SETTINGS', [], $this->adminTranslationDomain),
                ]
            );
        }

        // add sub menu
        $menu->setExtra('icon', '<i class="fa fa-cogs"></i>');
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
                    'label'           => $this->translator->trans($label, [], $this->adminTranslationDomain,),
                    'route'           => $route,
                    'routeParameters' => ['id' => $settings->getId()],
                ]
            );
    }
}
