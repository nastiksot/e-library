<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Repository\WishList\WishListRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

#[Route(
    path: '/test/wish-list',
    condition: "'dev' === '%kernel.environment%'"
)]
class WishListTestController extends AbstractTestController
{
    #[Route(
        path: '',
        name: 'test.wishlist',
    )]
    final public function index(
        RouterInterface $router,
        WishListRepository $wishListRepository
    ): Response {
        $data      = [];
        $wishLists = $wishListRepository->getAll();

        foreach ($wishLists as $wishList) {
            $data[$wishList->getId()] = [
                'user_id' => $wishList->getUser()?->getId(),
                'link'    => $router->generate(
                    'web.wishlist.details',
                    ['wishListUid' => $wishList->getUid()->toRfc4122()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ];
        }

        return $this->render(
            'test/test.html.twig',
            ['data' => $data]
        );
    }
}
