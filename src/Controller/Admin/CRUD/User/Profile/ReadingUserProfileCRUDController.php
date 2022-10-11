<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD\User\Profile;

use App\Controller\Admin\CRUD\AdminCRUDController;
use App\CQ\Command\Order\DoneBookOrderCommand;
use App\CQ\Command\Reading\ProlongCancelReadingCommand;
use App\CQ\Command\Reading\ProlongReadingCommand;
use App\Entity\Reading;
use App\Form\Type\Reading\ProlongReadingType;
use App\Service\MessageBusHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ReadingUserProfileCRUDController extends AdminCRUDController
{

    public function prolongAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): Response {
        /** @var Reading $reading */
        $reading = $this->assertObjectExists($request, true);
        if (!$reading) {
            throw $this->createNotFoundException('Reading Not Found');
        }

        $this->admin->checkAccess('prolong', $reading);
        $this->admin->setSubject($reading);

        $form = $this->createForm(ProlongReadingType::class, ['prolong_at' => $reading->getProlongAt()]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // save data
            if ($isFormValid) {
                try {
                    /** @var Reading $reading */
                    $reading = $messageBusHandler->handleCommand(
                        new ProlongReadingCommand(
                            readingId: (int)$reading->getId(),
                            prolongAt: $form->get('prolong_at')->getData(),
                        )
                    );

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->trans(
                            'READING_ENTITY.MESSAGE.PROLONG_SUCCESS',
                            [
                                '%book%'      => $this->escapeHtml($this->admin->toString($reading->getBook())),
                                '%prolongAt%' => $reading->getProlongAt()->format('Y-m-d'),
                            ],
                            'SonataAdminBundle'
                        )
                    );

                    // redirect to list
                    return $this->redirectToRoute('admin_user_reading_list');
                } catch (Throwable $e) {
                    $errorMessage = $e->getPrevious()?->getMessage() ?? $e->getMessage();
                    $this->addFlash(
                        'sonata_flash_error',
                        $this->trans($errorMessage, [], $this->admin->getTranslationDomain())
                    );
                }
            }
        }

        $formView = $form->createView();
        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('admin/user/profile/prolong.html.twig', [
            'action'   => 'prolong',
            'form'     => $formView,
            'object'   => $reading,
            'reading'  => $reading,
            'objectId' => $reading->getId(),
        ]);
    }


    public function prolongCancelAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): Response {
        /** @var Reading $reading */
        $reading = $this->assertObjectExists($request, true);
        if (!$reading) {
            throw $this->createNotFoundException('Reading Not Found');
        }

        $this->admin->checkAccess('prolong_cancel', $reading);
        $this->admin->setSubject($reading);

        if ($request->getMethod() === Request::METHOD_POST) {
            // check the csrf token
            $this->validateCsrfToken($request, 'admin.user.reading.prolong_cancel');

            try {
                /** @var Reading $reading */
                $reading = $messageBusHandler->handleCommand(
                    new ProlongCancelReadingCommand(
                        readingId: (int)$reading->getId(),
                    )
                );

                $this->addFlash(
                    'sonata_flash_success',
                    $this->trans(
                        'READING_ENTITY.MESSAGE.PROLONG_CANCEL.SUCCESS',
                        [
                            '%book%' => $this->escapeHtml($this->admin->toString($reading->getBook())),

                        ],
                        'SonataAdminBundle'
                    )
                );

                // redirect to list
                return $this->redirectToRoute('admin_user_reading_list');
            } catch (Throwable $e) {
                $errorMessage = $e->getPrevious()?->getMessage() ?? $e->getMessage();
                $this->addFlash(
                    'sonata_flash_error',
                    $this->trans($errorMessage, [], $this->admin->getTranslationDomain())
                );
            }
        }

        return $this->renderWithExtraParams('admin/user/profile/prolong_cancel.html.twig', [
            'object'     => $reading,
            'reading'    => $reading,
            'action'     => 'prolong_cancel',
            'csrf_token' => $this->getCsrfToken('admin.user.reading.prolong_cancel'),
        ]);
    }

}
