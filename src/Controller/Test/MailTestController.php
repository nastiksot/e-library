<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\CQ\Command\SendMail\DealerRequest\DealerRequestSendMailCommand;
use App\CQ\Command\SendMail\User\UserForgotPasswordSendMailCommand;
use App\CQ\Command\SendMail\User\UserRegisterNotificationSendMailCommand;
use App\CQ\Command\SendMail\User\UserRegisterSendMailCommand;
use App\Entity\DealerRequest\DealerRequest;
use App\EventSubscriber\UniqueIdentifierHandler;
use App\Repository\MailTemplate\DealerRequestMailTemplateRepository;
use App\Repository\MailTemplate\ForgotPasswordMailTemplateRepository;
use App\Repository\MailTemplate\RegisterMailTemplateRepository;
use App\Repository\MailTemplate\RegisterNotificationMailTemplateRepository;
use App\Repository\User\DealerRepository;
use App\Repository\User\UserRepository;
use App\Repository\WishList\WishListRepository;
use App\Service\Mail\MailReplacement\DealerRequestMailReplacement;
use App\Service\Mail\MailReplacement\ForgotPasswordMailReplacement;
use App\Service\Mail\MailReplacement\RegisterMailReplacement;
use App\Service\Mail\MailReplacement\RegisterNotificationMailReplacement;
use App\Service\MessageBusHandler;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Uid\UuidV4;
use function sprintf;

#[Route(
    path: '/test/mail',
)]
class MailTestController extends AbstractTestController
{
    #[Route(
        path: '/user-register/{id}/template',
        name: 'test.mail.user_register_template',
    )]
    final public function userRegisterTemplate(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        RegisterMailReplacement $mailReplacement,
        RegisterMailTemplateRepository $templateRepository,
    ): Response {
        $user = $userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('User ID "%s" not found.', $id));
        }

        // save token
        $token   = new UuidV4();
        $validAt = Carbon::today()->addMonth()->endOfDay();
        $user
            ->setForgotPasswordToken($token)
            ->setForgotPasswordValidAt($validAt)
            ->setRegisterConfirmToken($token)
            ->setRegisterConfirmValidAt($validAt);
        $em->flush();

        // resolve template
        $template = $templateRepository->getTemplate();
        $locale   = $user->getLocale();
        $uid      = UuidV4::fromString($request->attributes->get(UniqueIdentifierHandler::UID_NAME));
        $subject  = $mailReplacement->handleReplacements($uid, $user, $template->translate($locale)->getSubject());
        $message  = $mailReplacement->handleReplacements($uid, $user, $template->translate($locale)->getContent());

        return $this->render(
            'default/email/user-register-email.html.twig',
            [
                'subject' => $subject,
                'message' => $message,
            ]
        );
    }

    #[Route(
        path: '/user-register/{id}/send',
        name: 'test.mail.user_register_send',
    )]
    final public function userRegisterSend(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        MessageBusHandler $messageBusHandler,
    ): Response {
        $user = $userRepository->getOneById($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('User ID "%s" not found.', $id));
        }

        // save token
        $token   = new UuidV4();
        $validAt = Carbon::today()->addMonth()->endOfDay();
        $user
            ->setForgotPasswordToken($token)
            ->setForgotPasswordValidAt($validAt)
            ->setRegisterConfirmToken($token)
            ->setRegisterConfirmValidAt($validAt);
        $em->flush();

        // send
        $uid = UuidV4::fromString($request->attributes->get(UniqueIdentifierHandler::UID_NAME));
        $messageBusHandler->handleCommand(new UserRegisterSendMailCommand($uid, $user->getEmail()));

        $data = [
            'sent' => true,
        ];

        return $this->render(
            'test/test.html.twig',
            ['data' => $data]
        );
    }

    //////////////////////////////////////////////////////////////////

    #[Route(
        path: '/user-register-notification/{id}/template',
        name: 'test.mail.user_register_notification_template',
    )]
    final public function userRegisterNotificationTemplate(
        int $id,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        RegisterNotificationMailReplacement $mailReplacement,
        RegisterNotificationMailTemplateRepository $templateRepository,
    ): Response {
        $user = $userRepository->getOneById($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('User ID "%s" not found.', $id));
        }

        // save token
        $token   = new UuidV4();
        $validAt = Carbon::today()->addMonth()->endOfDay();
        $user
            ->setForgotPasswordToken($token)
            ->setForgotPasswordValidAt($validAt)
            ->setRegisterConfirmToken($token)
            ->setRegisterConfirmValidAt($validAt);
        $em->flush();

        // resolve template
        $template = $templateRepository->getTemplate();
        $locale   = $user->getLocale();
        $subject  = $mailReplacement->handleUserReplacements($user, $template->translate($locale)->getSubject());
        $message  = $mailReplacement->handleUserReplacements($user, $template->translate($locale)->getContent());

        return $this->render(
            'default/email/user-register-notification-email.html.twig',
            [
                'subject' => $subject,
                'message' => $message,
            ]
        );
    }

    #[Route(
        path: '/user-register-notification/{id}/send',
        name: 'test.mail.user_register_notification_send',
    )]
    final public function userRegisterNotificationSend(
        int $id,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        MessageBusHandler $messageBusHandler,
    ): Response {
        $user = $userRepository->getOneById($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('User ID "%s" not found.', $id));
        }

        // save token
        $token   = new UuidV4();
        $validAt = Carbon::today()->addMonth()->endOfDay();
        $user
            ->setForgotPasswordToken($token)
            ->setForgotPasswordValidAt($validAt)
            ->setRegisterConfirmToken($token)
            ->setRegisterConfirmValidAt($validAt);
        $em->flush();

        // send
        $messageBusHandler->handleCommand(new UserRegisterNotificationSendMailCommand($user->getEmail()));

        $data = [
            'sent' => true,
        ];

        return $this->render(
            'test/test.html.twig',
            ['data' => $data]
        );
    }

    //////////////////////////////////////////////////////////////////

    #[Route(
        path: '/forgot-password/{id}/template',
        name: 'test.mail.forgot-password_template',
    )]
    final public function forgotPasswordTemplate(
        int $id,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ForgotPasswordMailReplacement $mailReplacement,
        ForgotPasswordMailTemplateRepository $templateRepository,
    ): Response {
        $user = $userRepository->getOneById($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('User ID "%s" not found.', $id));
        }

        // save token
        $token   = new UuidV4();
        $validAt = Carbon::today()->addMonth()->endOfDay();
        $user
            ->setForgotPasswordToken($token)
            ->setForgotPasswordValidAt($validAt)
            ->setRegisterConfirmToken($token)
            ->setRegisterConfirmValidAt($validAt);
        $em->flush();

        // resolve template
        $template = $templateRepository->getTemplate();
        $locale   = $user->getLocale();
        $subject  = $mailReplacement->handleUserReplacements($user, $template->translate($locale)->getSubject());
        $message  = $mailReplacement->handleUserReplacements($user, $template->translate($locale)->getContent());

        return $this->render(
            'default/email/user-forgot-password-email.html.twig',
            [
                'subject' => $subject,
                'message' => $message,
            ]
        );
    }

    #[Route(
        path: '/forgot-password/{id}/send',
        name: 'test.mail.forgot_password_send',
    )]
    final public function forgotPasswordSend(
        int $id,
        EntityManagerInterface $em,
        UserRepository $userRepository,
        MessageBusHandler $messageBusHandler,
    ): Response {
        $user = $userRepository->getOneById($id);

        if (!$user) {
            throw $this->createNotFoundException(sprintf('User ID "%s" not found.', $id));
        }

        // save token
        $token   = new UuidV4();
        $validAt = Carbon::today()->addMonth()->endOfDay();
        $user
            ->setForgotPasswordToken($token)
            ->setForgotPasswordValidAt($validAt)
            ->setRegisterConfirmToken($token)
            ->setRegisterConfirmValidAt($validAt);
        $em->flush();

        // send
        $messageBusHandler->handleCommand(new UserForgotPasswordSendMailCommand($user->getEmail()));

        $data = [
            'sent' => true,
        ];

        return $this->render(
            'test/test.html.twig',
            ['data' => $data]
        );
    }

    //////////////////////////////////////////////////////////////////

    #[Route(
        path: '/dealer-request/{dealerId}/template',
        name: 'test.mail.dealer-request.template',
    )]
    #[Route(
        path: '/dealer-request-customer/{dealerId}/template',
        name: 'test.mail.dealer-request-customer.template',
    )]
    final public function dealerRequestTemplate(
        Request $request,
        int $dealerId,
        EntityManagerInterface $em,
        DealerRepository $dealerRepository,
        WishListRepository $wishListRepository,
        DealerRequestMailReplacement $mailReplacement,
        DealerRequestMailTemplateRepository $templateRepository,
        RouterInterface $router,
    ): Response {
        $dealer = $dealerRepository->getOneById($dealerId);

        if (!$dealer) {
            throw $this->createNotFoundException(sprintf('Dealer ID "%s" not found.', $dealerId));
        }

        $wishListUid = UuidV4::fromString($request->attributes->get(UniqueIdentifierHandler::UID_NAME));
        $wishList    = $wishListRepository->getOneByUid($wishListUid);

        if (!$wishList) {
            throw $this->createNotFoundException(sprintf('WishList UID "%s" not found.', $wishListUid->toRfc4122()));
        }

        // create dealer request
        $dealerRequest = (new DealerRequest())
            ->setDealer($dealer)
            ->setWishList($wishList)
            ->setLocale($request->getLocale())
            ->setUser($this->getUser())
            ->setContactName('Test Customer Name')
            ->setEmail('customer@example.com')
            ->setPhone('0123456789')
            ->setAddress('Customer Address 001')
            ->setMessage('Test Customer Message')
            ->setSendCopy(false);

        // save dealer request
        $em->persist($dealerRequest);
        $em->flush();

        // resolve template
        $template = $templateRepository->getTemplate();
        $locale   = $request->getLocale();

        // is customer copy
        $isCustomerCopy = 'test.mail.dealer-request-customer.template' === $request->attributes->get(
                '_canonical_route'
            );

        $dealerMessage = $template->translate($locale)->getDealerMessage();
        $params        = [
            'dealerMessage' => $mailReplacement->handleReplacements($dealerRequest, $dealerMessage),
            'dealerRequest' => $dealerRequest,
        ];

        if ($isCustomerCopy) {
            $params['subject']         = $mailReplacement->handleReplacements($dealerRequest, $template->translate($locale)->getCustomerSubject());
            $view                      = 'default/email/dealer-request-customer-message-email.html.twig';
            $customerMessage           = $template->translate($locale)->getCustomerMessage();
            $params['customerMessage'] = $mailReplacement->handleReplacements($dealerRequest, $customerMessage);
        } else {
            $params['subject']             = $mailReplacement->handleReplacements($dealerRequest, $template->translate($locale)->getDealerSubject());
            $view                          = 'default/email/dealer-request-dealer-message-email.html.twig';
            $dealerMessagePrefix           = $template->translate($locale)->getDealerMessagePrefix();
            $params['dealerMessagePrefix'] = $mailReplacement->handleReplacements($dealerRequest, $dealerMessagePrefix);
            $params['expertAreaLink']      = $router->generate('web.account.dealer.requests', [], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $this->render($view, $params);
    }

    #[Route(
        path: '/dealer-request/{dealerId}/send',
        name: 'test.mail.dealer-request.send',
    )]
    #[Route(
        path: '/dealer-request-customer/{dealerId}/send',
        name: 'test.mail.dealer-request-customer.send',
    )]
    final public function dealerRequestSend(
        Request $request,
        int $dealerId,
        EntityManagerInterface $em,
        DealerRepository $dealerRepository,
        WishListRepository $wishListRepository,
        MessageBusHandler $messageBusHandler,
    ): Response {
        $dealer = $dealerRepository->getOneById($dealerId);

        if (!$dealer) {
            throw $this->createNotFoundException(sprintf('Dealer ID "%s" not found.', $dealerId));
        }

        $wishListUid = UuidV4::fromString($request->attributes->get(UniqueIdentifierHandler::UID_NAME));
        $wishList    = $wishListRepository->getOneByUid($wishListUid);

        if (!$wishList) {
            throw $this->createNotFoundException(sprintf('WishList UID "%s" not found.', $wishListUid->toRfc4122()));
        }

        // is customer copy
        $isCustomerCopy = 'test.mail.dealer-request-customer.send' === $request->attributes->get('_canonical_route');

        // create dealer request
        $dealerRequest = (new DealerRequest())
            ->setDealer($dealer)
            ->setWishList($wishList)
            ->setLocale($request->getLocale())
            ->setUser($this->getUser())
            ->setContactName('Test Customer Name')
            ->setEmail('customer@example.com')
            ->setPhone('0123456789')
            ->setAddress('Customer Address 001')
            ->setMessage('Test Customer Message')
            ->setSendCopy($isCustomerCopy);

        // save dealer request
        $em->persist($dealerRequest);
        $em->flush();

        // send
        $messageBusHandler->handleCommand(new DealerRequestSendMailCommand($dealerRequest->getId()));

        $data = [
            'sent' => true,
        ];

        return $this->render(
            'test/test.html.twig',
            ['data' => $data]
        );
    }
}
