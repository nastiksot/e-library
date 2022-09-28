<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\User\Dealer;
use App\Entity\WishList\WishList;
use App\EventSubscriber\UniqueIdentifierHandler;
use App\Repository\MessageRepository;
use App\Repository\Page\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route(
        path: '/home-planer',
        name: 'web.home_planner',
        options: ['expose' => true]
    )]
    #[Route(
        path: '/dealer/{dealerSlug}/home-planer',
        name: 'web.dealer.home_planner',
        options: ['expose' => true]
    )]
    final public function homePlanner(Request $request, MessageRepository $messageRepository, ?Dealer $dealer = null): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'NAVIGATION.SMART_HOME_PLANNER');

        return $this->render('default/index.html.twig', ['dealer_uid' => $dealer?->getUid(), 'title' => $title]);
    }

    #[Route(
        path: '',
        name: 'web.homepage',
        options: ['expose' => true]
    )]
    #[Route(
        path: '/dealer/{dealerSlug}',
        name: 'web.dealer.homepage',
        options: ['expose' => true]
    )]
    final public function dayWithSomfy(Request $request, MessageRepository $messageRepository, ?Dealer $dealer = null): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'NAVIGATION.DAY_WITH_SOMFY');

        return $this->render('default/day-with-somfy.html.twig', ['dealer_uid' => $dealer?->getUid(), 'title' => $title]);
    }

    #[Route(
        path: '/wish-list',
        name: 'web.wishlist',
        options: ['expose' => true]
    )]
    #[Route(
        path: '/dealer/{dealerSlug}/wish-list',
        name: 'web.dealer.wishlist',
        options: ['expose' => true]
    )]
    final public function wishList(Request $request, MessageRepository $messageRepository, ?Dealer $dealer = null): RedirectResponse
    {
        if (null !== $dealer) {
            return $this->redirectToRoute(
                'web.dealer.wishlist.details',
                [
                    'dealerSlug'  => $dealer->getSlug(),
                    'wishListUid' => $request->attributes->get(UniqueIdentifierHandler::UID_NAME),
                ]
            );
        }

        $title = $messageRepository->getMessageByCode($request->getLocale(), 'NAVIGATION.WISH_LIST');

        return $this->redirectToRoute(
            'web.wishlist.details',
            [
                'wishListUid' => $request->attributes->get(UniqueIdentifierHandler::UID_NAME),
                'title'       => $title,
            ]
        );
    }

    #[Route(
        path: '/wish-list/{wishListUid}',
        name: 'web.wishlist.details',
        requirements: ['wishListUid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
        options: ['expose' => true],
    )]
    #[Route(
        path: '/dealer/{dealerSlug}/wish-list/{wishListUid}',
        name: 'web.dealer.wishlist.details',
        requirements: ['wishListUid' => '[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}'],
        options: ['expose' => true]
    )]
    final public function wishListDetails(
        string $wishListUid,
        Request $request,
        MessageRepository $messageRepository,
        ?Dealer $dealer = null,
        ?WishList $wishList = null,
    ): Response {
        if (null === $wishList) {
            throw $this->createNotFoundException();
        }

        $request->attributes->set(UniqueIdentifierHandler::UID_NAME, $wishListUid);
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'NAVIGATION.WISH_LIST');

        return $this->render('default/wishlist.html.twig', [
            'dealer_uid' => $dealer?->getUid(),
            'title'      => $title,
        ]);
    }

    #[Route(
        path: '/contact-us',
        name: 'web.contact_us',
        options: ['expose' => true]
    )]
    final public function contactUs(): Response
    {
        return $this->render('default/contact-us.html.twig');
    }

    #[Route(
        path: '/{slug}',
        name: 'web.cms',
        options: ['expose' => true],
        priority: -1
    )]
    final public function cms(
        Request $request,
        string $slug,
        PageRepository $pageRepository
    ): Response {
        $page = $pageRepository->getOneBySlug($slug);

        if (null === $page) {
            throw $this->createNotFoundException('Page not found');
        }

        if ($page->getRedirect()) {
            return $this->redirect($page->getRedirect());
        }

        $title = $page->translate($request->getLocale())?->getTitle();

        return $this->render('default/cms.html.twig', ['page' => $page, 'title' => $title]);
    }

    #[Route(
        path: '/test-email',
    )]
    public function testEmail(): Response
    {
        return $this->render('email/request.twig');
    }
}
