<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD\User;

use App\CQ\Command\User\RegisterUserCommand;
use App\Entity\User\User;
use App\Form\Type\User\ReaderLoginUserType;
use App\Form\Type\User\ReaderRegisterUserType;
use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Throwable;

final class UserReaderCRUDController extends AbstractUserCRUDController
{
    public function __construct(
        private AuthenticatorInterface $authenticator
    ) {
    }

    public function loginAction(
        AuthenticationUtils $authUtils
    ): Response {
        $error        = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();
        $form         = $this->createForm(ReaderLoginUserType::class);
        $formView     = $form->createView();

        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('admin/user/reader-login.html.twig', [
            'action'        => 'login',
            'form'          => $formView,
            'object'        => null,
            'objectId'      => null,
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    public function registerAction(
        Request $request,
        UserRepository $userRepository,
        UserAuthenticatorInterface $userAuthenticator,
    ): Response {
        $form = $this->createForm(ReaderRegisterUserType::class, null, ['userRepository' => $userRepository]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // save data
            if ($isFormValid) {
                try {
                    /** @var User */
                    $user = $this->messageBusHandler->handleCommand(
                        new RegisterUserCommand(
                            firstName: $form->get('first_name')->getData(),
                            lastName:  $form->get('last_name')->getData(),
                            email:     $form->get('email')->getData(),
                            password:  $form->get('password')->getData(),
                        )
                    );

                    return $userAuthenticator->authenticateUser($user, $this->authenticator, $request);
                } catch (Throwable $e) {
                    $this->addFlash(
                        'sonata_flash_error',
                        $this->trans($e->getMessage(), [], $this->admin->getTranslationDomain())
                    );
                }
            }

            if (!$isFormValid) {
                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans('USER_ENTITY.ERROR.REGISTER', [], $this->admin->getTranslationDomain())
                );
            }
        }


        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('admin/user/reader-register.html.twig', [
            'action'   => 'register',
            'form'     => $formView,
            'object'   => null,
            'objectId' => null,
        ]);
    }

}
