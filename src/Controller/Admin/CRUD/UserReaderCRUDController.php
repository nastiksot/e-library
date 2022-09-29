<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD;

use App\CQ\Command\User\RegisterUserCommand;
use App\Form\Type\User\ReaderLoginUserType;
use App\Form\Type\User\ReaderRegisterUserType;
use App\Repository\User\UserRepository;
use App\Service\MessageBusHandler;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Throwable;

final class UserReaderCRUDController extends CRUDController
{

    public function loginAction(
        AuthenticationUtils $authUtils
    ): Response {
        $error        = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();


        $form = $this->createForm(ReaderLoginUserType::class);

        $formView = $form->createView();
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
        MessageBusHandler $messageBusHandler,
        UserRepository $userRepository
    ): Response {
        $form = $this->createForm(ReaderRegisterUserType::class, null, ['userRepository' => $userRepository]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // save data
            if ($isFormValid) {
                try {
                    $messageBusHandler->handleCommand(
                        new RegisterUserCommand(
                            firstName: $form->get('first_name')->getData(),
                            lastName:  $form->get('last_name')->getData(),
                            email:     $form->get('email')->getData(),
                            password:  $form->get('password')->getData(),
                        )
                    );
                    // todo: return redirect

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
