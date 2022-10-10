<?php

declare(strict_types=1);

namespace App\Controller\Admin\CRUD;

use App\CQ\Command\Order\CreateBookOrderCommand;
use App\Entity\Book\Book;
use App\Entity\Order;
use App\Entity\User\User;
use App\Form\Type\OrderBookType;
use App\Service\MessageBusHandler;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @method User|null getUser()
 */
final class BookCRUDController extends CRUDController
{

    public function orderAction(
        Request $request,
        MessageBusHandler $messageBusHandler,
    ): Response {
        /** @var Book $book */
        $book = $this->assertObjectExists($request, true);
        if (!$book) {
            throw $this->createNotFoundException('Book Not Found');
        }

        $this->admin->checkAccess('order', $book);
        $this->admin->setSubject($book);

        $options = ['book_id' => $book->getId(), 'user_id' => $this->getUser()?->getId()];
        $form    = $this->createForm(OrderBookType::class, null, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();

            // save data
            if ($isFormValid) {
                try {
                    /** @var Order $order */
                    $order = $messageBusHandler->handleCommand(
                        new CreateBookOrderCommand(
                            bookId:      (int)$form->get('book_id')->getData(),
                            userId:      (int)$form->get('user_id')->getData(),
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

        return $this->renderWithExtraParams('admin/book/order.html.twig', [
            'action'   => 'order',
            'form'     => $formView,
            'object'   => $book,
            'book'     => $book,
            'objectId' => $book->getId(),
        ]);
    }
}
