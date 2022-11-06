<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Controller\AbstractController;
use App\Repository\MessageRepository;
use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/account')]
class UserController extends AbstractController
{
    #[Route(
        path: '/dealer/requests',
        name: 'web.account.dealer.requests',
        options: ['expose' => true],
    )]
    final public function requests(
        Request $request,
        MessageRepository $messageRepository,
        int $isArchivedRequestsPage = 0): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'DEALER_REQUEST.TITLE');

        return $this->render('default/user/dealer-request.html.twig', [
            'isArchivedRequestsPage' => $isArchivedRequestsPage,
            'title'                  => $title,
        ]);
    }

    #[Route(
        path: '/dealer/archived-requests',
        name: 'web.account.dealer.archivedRequests',
        options: ['expose' => true],
    )]
    final public function archivedRequests(): Response
    {
        return $this->requests(1);
    }

    #[Route(
        path: '/wish-list',
        name: 'web.account.wishlist',
        options: ['expose' => true],
    )]
    final public function wishlists(Request $request, MessageRepository $messageRepository): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'USER.WISHLISTS.UPDATE.TITLE');

        return $this->render('default/user/wish-list.html.twig', ['title' => $title]);
    }

    #[Route(
        path: '',
        name: 'web.account',
        options: ['expose' => true],
    )]
    final public function account(Request $request, MessageRepository $messageRepository): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'USER.ACCOUNT.TITLE');

        return $this->render('default/user/account.html.twig', ['title' => $title]);
    }

    #[Route(
        path: '/dealer/users',
        name: 'web.account.dealer.users',
        options: ['expose' => true],
    )]
    final public function dealerUsersList(Request $request, MessageRepository $messageRepository): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'USER.ACCOUNT.MENU.DEALER_LIST');

        return $this->render('default/user/dealer-list.html.twig', ['title' => $title]);
    }

    #[Route(
        path: '/dealer/users/{id}',
        name: 'web.account.dealer.user.get',
        requirements: ['id' => '\d+'],
        options: ['expose' => true],
    )]
    final public function dealerUser(
        int $id,
        UserRepository $userRepository,
        Request $request,
        MessageRepository $messageRepository
    ): Response {
        $user = $userRepository->findOneBy(['id' => $id]);

        if (null === $user) {
            throw $this->createNotFoundException();
        }

        $title = $messageRepository->getMessageByCode($request->getLocale(), 'DEALER_USER.ACCOUNT.EDIT.TITLE');

        return $this->render('default/user/dealer-account.html.twig', [
            'title'        => $title,
            'dealerUserId' => $user->getId(),
        ]);
    }

    #[Route(
        path: '/dealer/users/create',
        name: 'web.account.dealer.user.create',
        options: ['expose' => true],
    )]
    final public function newDealerUser(Request $request, MessageRepository $messageRepository): Response
    {
        $title = $messageRepository->getMessageByCode($request->getLocale(), 'DEALER_USER.ACCOUNT.EDIT.TITLE');

        return $this->render('default/user/dealer-account-new.html.twig', ['title' => $title]);
    }
}
