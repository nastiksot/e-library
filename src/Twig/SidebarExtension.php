<?php

declare(strict_types=1);

namespace App\Twig;

use App\DTO\MenuItem;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SidebarExtension extends AbstractExtension
{
    protected RouterInterface       $router;
    protected RequestStack          $requestStack;
    protected TokenStorageInterface $tokenStorage;

    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage
    ) {
        $this->router       = $router;
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('showSidebar', [$this, 'showSidebar'], ['needs_environment' => true]),
        ];
    }

    private function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();
        if ($token && ($user = $token->getUser()) && $user instanceof User) {
            return $user;
        }

        return null;
    }

    public function showSidebar(Environment $environment): string
    {
        $user    = $this->getUser();
        $menuMap = [];

        // menu entry library-settings
        if ($user && $user->isAdmin()) {
            $menuMap['library-settings'] = [
                'route'   => null,
                'title'   => 'Library Settings',
                'subMenu' => [
                    [
                        'route' => 'admin.list',
                        'title' => 'Admins',
                        'extra' => ['admin.add', 'admin.edit']
                    ],
                    [
                        'route' => 'librarian.list',
                        'title' => 'Librarians',
                        'extra' => ['librarian.add', 'librarian.edit']
                    ],
                    [
                        'route' => 'reader.list',
                        'title' => 'Readers',
                        'extra' => ['reader.add', 'reader.edit']
                    ],
                    [
                        'route' => 'author.list',
                        'title' => 'Authors',
                        'extra' => ['author.add', 'author.edit']
                    ],
                    [
                        'route' => 'book.list',
                        'title' => 'Authors',
                        'extra' => ['book.add', 'book.edit']
                    ],
                ],
            ];
        }

        // menu entry reading
        if ($user && $user->isLibrarian()) {
            $menuMap['reading'] = [
                'route'   => 'reading.list',
                'title'   => 'Reading',
                'subMenu' => [],
            ];
        }

        // menu entry books
        $menuMap['books'] = [
            'route'   => 'books',
            'title'   => 'Books',
            'subMenu' => [],
        ];

        // menu entry reading
        if (!$user) {
            $menuMap['register'] = [
                'route'   => 'register',
                'title'   => 'Register',
                'subMenu' => [],
            ];
            $menuMap['login'] = [
                'route'   => 'login',
                'title'   => 'Login',
                'subMenu' => [],
            ];
        }

        // menu entry reading
        if ($user && $user->isUser()) {
            $menuMap['reading-list-my'] = [
                'route'   => 'reading.list.my',
                'title'   => 'I am Reading',
                'subMenu' => [],
            ];
            $menuMap['account'] = [
                'route'   => null,
                'title'   => 'Account',
                'subMenu' => [
                    [
                        'route' => 'profile',
                        'title' => 'Profile',
                        'extra' => [],
                    ],
                    [
                        'route' => 'logout',
                        'title' => 'Logout',
                        'extra' => [],
                    ],
                ],
            ];
        }

        // build menu
        $menus   = [];
        $request = $this->requestStack->getMasterRequest();
        $route   = $request ? $request->attributes->get('_route') : null;
        foreach ($menuMap as $key => $menu) {
            $menuRoute = $menu['route'] ?? null;
            $url       = $menuRoute ? $this->router->generate($menuRoute) : '#';
            $title     = $menu['title'] ?? null;
            $active    = $route && $menuRoute && $route === $menuRoute;
            $subMenus  = [];
            $open      = false;
            foreach ($menu['subMenu'] as $subMenu) {
                $subRoute  = $subMenu['route'] ?? null;
                $subUrl    = $subRoute ? $this->router->generate($subRoute) : '#';
                $subActive = $route && $subRoute && $route === $subRoute;
                foreach ($subMenu['extra'] as $extraRoute) {
                    if ($route && $route === $extraRoute) {
                        $open      = true;
                        $subActive = true;
                    }
                }
                $subMenus[] = new MenuItem($subUrl, $subMenu['title'],  $subActive, $open);
                if ($subActive) {
                    $open = true;
                    $active = $subActive;
                }
            }

            $menus[] = new MenuItem($url, $title, $active, $open, $subMenus);
        }

        return $environment->render(
            'default/_partials/sidebar.html.twig',
            ['menus' => $menus,]
        );
    }

}
