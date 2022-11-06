<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD\Book;

use App\Controller\Admin\CRUD\AdminCRUDController;
use App\CQ\Command\Order\CreateOrderCommand;
use App\Entity\Book\Book;
use App\Entity\Order;
use App\Form\Type\OrderBookType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class BookCRUDController extends AdminCRUDController
{

    public function orderAction(
        Request $request,
    ): Response {
        /** @var Book $book */
        $book = $this->assertObjectExists($request, true);
        if (!$book) {
            throw $this->createNotFoundException('Book Not Found');
        }

        $this->admin->checkAccess('order', $book);
        $this->admin->setSubject($book);

        $form = $this->createForm(OrderBookType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // save data
            if ($isFormValid) {
                try {
                    /** @var Order $order */
                    $order = $this->messageBusHandler->handleCommand(
                        new CreateOrderCommand(
                            bookId:      (int)$book->getId(),
                            userId:      (int)$this->getUser()?->getId(),
                            quantity:    (int)$form->get('quantity')->getData(),
                            readingType: (string)$form->get('reading_type')->getData(),
                            startAt:     $form->get('start_at')->getData(),
                            endAt:       $form->get('end_at')->getData(),
                        )
                    );

                    $this->addFlash(
                        'sonata_flash_success',
                        $this->trans(
                            'BOOK_ENTITY.MESSAGE.ORDER_SUCCESS',
                            [
                                '%name%'  => $this->escapeHtml($this->admin->toString($book)),
                                '%order%' => $order->getId(),
                            ],
                            'SonataAdminBundle'
                        )
                    );

                    // redirect to list
                    return $this->redirectToRoute('admin_book_list');
                } catch (Throwable $e) {
                    $errorMessage = $this->exceptionFactory->getLastPreviousMessage($e);
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

        return $this->renderWithExtraParams('admin/book/order.html.twig', [
            'action'   => 'order',
            'form'     => $formView,
            'object'   => $book,
            'book'     => $book,
            'objectId' => $book->getId(),
        ]);
    }
}
